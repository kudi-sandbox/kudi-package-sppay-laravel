# sppay-laravel

Laravel client for the [SPPay](https://sppay.dev) public API: OAuth, payments, transfers, transactions, institutions, and SMS.

**Package:** `mboateng/sppay-laravel`  
**PHP:** 8.1+  
**Laravel:** 10, 11, or 12

## Installation

```bash
composer require mboateng/sppay-laravel
```

The service provider and `Sppay` facade are registered automatically via Laravel package discovery.

Publish the configuration (optional):

```bash
php artisan vendor:publish --tag=sppay-config
```

## Configuration

Environment variables (see `config/sppay.php`):

| Variable | Description | Default |
|----------|-------------|---------|
| `SPPAY_BASE_URL` | API host, no trailing slash | `https://engine.sppay.dev` |
| `SPPAY_ACCESS_TOKEN` | Bearer token from `oauthToken()` | — |
| `SPPAY_TIMEOUT` | Request timeout (seconds) | `30` |
| `SPPAY_CONNECT_TIMEOUT` | Connect timeout (seconds) | `10` |

## Usage

### Facade

```php
use Mboateng\Sppay\Facades\Sppay;

$token = Sppay::oauthToken([
    'grant_type' => 'password',
    'client_id' => config('services.sppay.client_id'),
    'client_secret' => config('services.sppay.client_secret'),
    'username' => config('services.sppay.username'),
    'password' => config('services.sppay.password'),
]);

Sppay::setAccessToken($token['access_token']);

$transactions = Sppay::listTransactions(['page_size' => 10]);
```

### Dependency injection

```php
use Mboateng\Sppay\Contracts\SppayClientContract;

public function __construct(
    private SppayClientContract $sppay
) {}

public function example(): void
{
    $this->sppay->setAccessToken($token);
    $this->sppay->getTransaction('REFERENCE');
}
```

### Manual client

```php
use Mboateng\Sppay\SppayClient;

$client = new SppayClient(
    baseUrl: 'https://engine.sppay.dev',
    accessToken: $token,
);

$client->initiatePayment([/* ... */]);
```

### Public endpoints (no Bearer)

`oauthToken()` and `validatePublicKey()` do not send an `Authorization` header. All other methods require a Bearer token (`setAccessToken()` or `SPPAY_ACCESS_TOKEN`).

## API methods

| Method | Endpoint |
|--------|----------|
| `oauthToken(array $payload)` | `POST /oauth/token` |
| `validatePublicKey(array $body)` | `POST /v1/key/validate` |
| `validatePayment(array $body)` | `POST /v1/api/payments/validate` |
| `initiatePayment(array $body)` | `POST /v1/api/payments/initiate` |
| `submitPaymentOtp(array $body)` | `POST /v1/api/payments/otp/submit` |
| `validateTransferAccount(array $body)` | `POST /v1/api/transfers/validate-account` |
| `validateTransfer(array $body)` | `POST /v1/api/transfers/validate` |
| `submitTransfer(array $body)` | `POST /v1/api/transfers/submit` |
| `listTransactions(array $query)` | `GET /v1/api/transactions` |
| `getTransaction(string $reference)` | `GET /v1/api/transactions/{reference}` |
| `getTransactionStatus(string $reference)` | `GET /v1/api/transactions/{reference}/status` |
| `postSpPayWebhook(array $body)` | `POST /v1/sp-pay/webhook` |
| `getInstitutions(array $body)` | `POST /v1/api/institutions` |
| `getInstitution(string $code)` | `GET /v1/api/institutions/{code}` |
| `sendSms(array $body)` | `POST /v1/api/sms/send` |

`listTransactions()` accepts query parameters such as `account_no`, `reference`, `amount`, `date_from`, `date_to`, `type_code`, `status_id`, `page_size`, `page`, etc.

## Errors

Failed HTTP calls throw `Mboateng\Sppay\Exceptions\SppayRequestException`. Use `getResponseBody()` for the raw API error body when available.

## Testing

```bash
composer test
```

## License

MIT
