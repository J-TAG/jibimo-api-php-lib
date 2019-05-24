<?php


namespace puresoft\jibimo;


use puresoft\jibimo\models\pay\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\pay\ExtendedPayTransactionResponse;
use puresoft\jibimo\models\pay\PayTransactionRequest;
use puresoft\jibimo\models\pay\PayTransactionResponse;
use puresoft\jibimo\models\request\RequestTransactionRequest;
use puresoft\jibimo\models\request\RequestTransactionResponse;
use puresoft\jibimo\models\verification\ExtendedPayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\PayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\RequestTransactionVerificationResponse;
use puresoft\jibimo\payment\JibimoPay;
use puresoft\jibimo\payment\JibimoRequest;

class Jibimo
{
    /**
     * @param string $baseUrl
     * @param string $token
     * @param string $mobileNumber
     * @param int $amountInToman
     * @param string $privacy
     * @param string $trackerId
     * @param string|null $description
     * @param string|null $returnUrl
     * @return models\request\RequestTransactionResponse
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function request(string $baseUrl, string $token, string $mobileNumber, int $amountInToman, string $privacy,
                                   string $trackerId, ?string $description = null, ?string $returnUrl = null)
    : RequestTransactionResponse
    {
        $jibimoRequest = new JibimoRequest();

        $request = new RequestTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $trackerId, $description, $returnUrl);

        return $jibimoRequest->request($request);
    }

    /**
     * @param string $baseUrl
     * @param string $token
     * @param string $mobileNumber
     * @param int $amountInToman
     * @param string $privacy
     * @param string $trackerId
     * @param string|null $description
     * @return PayTransactionResponse
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function pay(string $baseUrl, string $token, string $mobileNumber, int $amountInToman, string $privacy,
                               string $trackerId, ?string $description = null)
    : PayTransactionResponse
    {
        $jibimoPay = new JibimoPay();

        $request = new PayTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $trackerId, $description);

        return $jibimoPay->pay($request);
    }

    /**
     * @param string $baseUrl
     * @param string $token
     * @param string $mobileNumber
     * @param int $amountInToman
     * @param string $privacy
     * @param string $iban
     * @param string $trackerId
     * @param string|null $description
     * @param string|null $name
     * @param string|null $family
     * @return ExtendedPayTransactionResponse
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidIbanException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function extendedPay(string $baseUrl, string $token, string $mobileNumber, int $amountInToman,
                                       string $privacy, string $iban, string $trackerId, ?string $description = null,
                                       ?string $name = null, ?string $family = null)
    : ExtendedPayTransactionResponse
    {
        $jibimoPay = new JibimoPay();

        $request = new ExtendedPayTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $iban, $trackerId, $description, $name, $family);

        return $jibimoPay->extendedPay($request);
    }

    /**
     * @return RequestTransactionVerificationResponse
     */
    public static function validateRequest(): RequestTransactionVerificationResponse
    {
        // TODO: Validate request transaction here
    }

    /**
     * @return PayTransactionVerificationResponse
     */
    public static function validatePay(): PayTransactionVerificationResponse
    {
        // TODO: Validate pay transaction here
    }

    /**
     * @return ExtendedPayTransactionVerificationResponse
     */
    public static function validateExtendedPay(): ExtendedPayTransactionVerificationResponse
    {
        // TODO: Validate extended pay transaction here
    }
}