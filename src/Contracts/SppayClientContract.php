<?php

namespace Mboateng\Sppay\Contracts;

interface SppayClientContract
{
    public function setAccessToken(?string $token): static;

    public function oauthToken(array $payload): array;

    public function validatePublicKey(array $body): array;

    public function validatePayment(array $body): array;

    public function initiatePayment(array $body): array;

    public function submitPaymentOtp(array $body): array;

    public function validateTransferAccount(array $body): array;

    public function validateTransfer(array $body): array;

    public function submitTransfer(array $body): array;

    public function listTransactions(array $query = []): array;

    public function getTransaction(string $reference): array;

    public function getTransactionStatus(string $reference): array;

    public function postSpPayWebhook(array $body = []): array;

    public function getInstitutions(array $body = []): array;

    public function getInstitution(string $code): array;

    public function sendSms(array $body): array;
}
