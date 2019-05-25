<?php


namespace puresoft\jibimo\api;

use puresoft\jibimo\internals\CurlResult;

interface RequestService
{
    public function request(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                            string $trackerId, ?string $description = null, ?string $returnUrl = null): CurlResult;

    public function validateRequest(string $baseUrl, string $token, int $transactionId): CurlResult;
}