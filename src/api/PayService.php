<?php


namespace puresoft\jibimo\api;

use puresoft\jibimo\internals\CurlResult;

interface PayService
{
    public function pay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                        string $trackerId, ?string $description = null): CurlResult;

    public function validatePay(string $baseUrl, string $token, int $transactionId): CurlResult;

    public function extendedPay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $iban, string $trackerId, ?string $description = null,
                                ?string $name = null, ?string $family = null): CurlResult;

    public function validateExtendedPay(string $baseUrl, string $token, int $transactionId): CurlResult;


}