<?php

namespace Mboateng\Sppay\Facades;

use Illuminate\Support\Facades\Facade;
use Mboateng\Sppay\Contracts\SppayClientContract;

/**
 * @method static \Mboateng\Sppay\SppayClient setAccessToken(?string $token)
 * @method static array oauthToken(array $payload)
 * @method static array oauthPasswordGrant()
 * @method static array validatePublicKey(array $body)
 * @method static array validatePayment(array $body)
 * @method static array initiatePayment(array $body)
 * @method static array submitPaymentOtp(array $body)
 * @method static array validateTransferAccount(array $body)
 * @method static array validateTransfer(array $body)
 * @method static array submitTransfer(array $body)
 * @method static array listTransactions(array $query = [])
 * @method static array getTransaction(string $reference)
 * @method static array getTransactionStatus(string $reference)
 * @method static array postSpPayWebhook(array $body = [])
 * @method static array getInstitutions(array $body = [])
 * @method static array getInstitution(string $code)
 * @method static array sendSms(array $body)
 *
 * @see \Mboateng\Sppay\SppayClient
 */
class Sppay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SppayClientContract::class;
    }
}
