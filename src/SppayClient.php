<?php

namespace Mboateng\Sppay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Mboateng\Sppay\Contracts\SppayClientContract;
use Mboateng\Sppay\Exceptions\SppayRequestException;

class SppayClient implements SppayClientContract
{
    protected Client $http;

    protected string $baseUrl;

    protected ?string $accessToken = null;

    /**
     * @param  array<string, mixed>  $guzzleConfig  Extra options passed to Guzzle (e.g. verify, proxy).
     */
    public function __construct(
        string $baseUrl,
        ?string $accessToken = null,
        float $timeout = 30.0,
        float $connectTimeout = 10.0,
        array $guzzleConfig = []
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->accessToken = $accessToken;

        $this->http = new Client(array_merge([
            RequestOptions::TIMEOUT => $timeout,
            RequestOptions::CONNECT_TIMEOUT => $connectTimeout,
            RequestOptions::HTTP_ERRORS => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ], $guzzleConfig));
    }

    public function setAccessToken(?string $token): static
    {
        $this->accessToken = $token;

        return $this;
    }

    /**
     * POST /oauth/token — password grant (no Bearer).
     *
     * @param  array<string, mixed>  $payload  grant_type, client_id, client_secret, username, password
     * @return array<string, mixed>
     */
    public function oauthToken(array $payload): array
    {
        return $this->request('POST', '/oauth/token', [
            RequestOptions::JSON => $payload,
        ], false);
    }

    /**
     * POST /v1/key/validate
     *
     * @param  array<string, mixed>  $body  client_id, key
     */
    public function validatePublicKey(array $body): array
    {
        return $this->request('POST', '/v1/key/validate', [
            RequestOptions::JSON => $body,
        ], false);
    }

    /**
     * POST /v1/api/payments/validate
     */
    public function validatePayment(array $body): array
    {
        return $this->request('POST', '/v1/api/payments/validate', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * POST /v1/api/payments/initiate
     */
    public function initiatePayment(array $body): array
    {
        return $this->request('POST', '/v1/api/payments/initiate', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * POST /v1/api/payments/otp/submit
     */
    public function submitPaymentOtp(array $body): array
    {
        return $this->request('POST', '/v1/api/payments/otp/submit', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * POST /v1/api/transfers/validate-account
     */
    public function validateTransferAccount(array $body): array
    {
        return $this->request('POST', '/v1/api/transfers/validate-account', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * POST /v1/api/transfers/validate
     */
    public function validateTransfer(array $body): array
    {
        return $this->request('POST', '/v1/api/transfers/validate', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * POST /v1/api/transfers/submit
     */
    public function submitTransfer(array $body): array
    {
        return $this->request('POST', '/v1/api/transfers/submit', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * GET /v1/api/transactions
     *
     * @param  array<string, scalar|null>  $query  account_no, reference, amount, date_from, date_to, type_code, status_id, is_scheduled, pending_actions, sort_column, sort_direction, export, page_size, page, etc.
     */
    public function listTransactions(array $query = []): array
    {
        return $this->request('GET', '/v1/api/transactions', [
            RequestOptions::QUERY => array_filter($query, static fn ($v) => $v !== null && $v !== ''),
        ]);
    }

    /**
     * GET /v1/api/transactions/{reference}
     */
    public function getTransaction(string $reference): array
    {
        return $this->request('GET', $this->transactionPath($reference));
    }

    /**
     * GET /v1/api/transactions/{reference}/status
     */
    public function getTransactionStatus(string $reference): array
    {
        return $this->request('GET', $this->transactionPath($reference).'/status');
    }

    /**
     * POST /v1/sp-pay/webhook — callback/test endpoint from collection.
     */
    public function postSpPayWebhook(array $body = []): array
    {
        $opts = [];
        if ($body !== []) {
            $opts[RequestOptions::JSON] = $body;
        }

        return $this->request('POST', '/v1/sp-pay/webhook', $opts);
    }

    /**
     * POST /v1/api/institutions
     */
    public function getInstitutions(array $body = []): array
    {
        $payload = $body === [] ? ['type_code' => ''] : $body;

        return $this->request('POST', '/v1/api/institutions', [
            RequestOptions::JSON => $payload,
        ]);
    }

    /**
     * GET /v1/api/institutions/{code}
     */
    public function getInstitution(string $code): array
    {
        return $this->request('GET', '/v1/api/institutions/'.$this->encodePathSegment($code));
    }

    /**
     * POST /v1/api/sms/send
     */
    public function sendSms(array $body): array
    {
        return $this->request('POST', '/v1/api/sms/send', [
            RequestOptions::JSON => $body,
        ]);
    }

    /**
     * @param  array<string, mixed>  $options  Guzzle request options
     * @return array<string, mixed>
     */
    protected function request(string $method, string $uri, array $options = [], bool $withBearer = true): array
    {
        if ($withBearer) {
            if ($this->accessToken === null || $this->accessToken === '') {
                throw new SppayRequestException('SPPay access token is not set. Call setAccessToken() or pass a token in config.');
            }
            $options['headers'] = array_merge($options['headers'] ?? [], [
                'Authorization' => 'Bearer '.$this->accessToken,
            ]);
        }

        $url = $this->baseUrl.$uri;

        try {
            $response = $this->http->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw SppayRequestException::fromGuzzle($e);
        }

        $body = (string) $response->getBody();
        if ($body === '') {
            return [];
        }

        $decoded = json_decode($body, true);
        if (! is_array($decoded)) {
            throw new SppayRequestException('Invalid JSON response from SPPay API.', (int) $response->getStatusCode(), null, $body);
        }

        return $decoded;
    }

    protected function transactionPath(string $reference): string
    {
        return '/v1/api/transactions/'.$this->encodePathSegment($reference);
    }

    protected function encodePathSegment(string $segment): string
    {
        return rawurlencode($segment);
    }
}
