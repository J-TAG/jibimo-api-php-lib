<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\api\Request;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\models\pay\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\pay\ExtendedPayTransactionResponse;
use puresoft\jibimo\models\pay\PayTransactionRequest;
use puresoft\jibimo\models\pay\PayTransactionResponse;
use puresoft\jibimo\models\request\RequestTransactionRequest;
use puresoft\jibimo\models\request\RequestTransactionResponse;
use puresoft\jibimo\payment\JibimoPay;
use puresoft\jibimo\payment\JibimoRequest;
use puresoft\jibimo\payment\JibimoValidationResult;
use puresoft\jibimo\payment\JibimoValidator;

class Jibimo
{
    /**
     * This method will request money from a mobile number whose owner may or may not be registered in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number that want to request money from.
     * @param int $amountInToman Amount of transaction in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction. This can be
     * your factor number.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $returnUrl The URL to return after payment. If you leave this URL blank, Jibimo will redirect
     * user to your company homepage.
     * @return models\request\RequestTransactionResponse Response of Jibimo API in a data model object.
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
        $jibimoRequest = new JibimoRequest(new Request(new CurlRequest()));

        $request = new RequestTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $trackerId, $description, $returnUrl);

        return $jibimoRequest->request($request);
    }

    /**
     * This method will pay money to a mobile number whose owner may or may not be registered in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number that want to pay money to.
     * @param int $amountInToman Amount of transaction in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction. This can be
     * your factor number.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @return PayTransactionResponse Response of Jibimo API in a data model object.
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
        $jibimoPay = new JibimoPay(new Pay(new CurlRequest()));

        $request = new PayTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $trackerId, $description);

        return $jibimoPay->pay($request);
    }

    /**
     * This method will pay money directly to a combination of mobile number and IBAN (Sheba) number whose owner may or
     * may not be registered in Jibimo and after this transaction money will be automatically transferred to IBAN
     * owner's bank account using Paya system, which will take up to 72 hours to be transferred.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number that want to pay money to.
     * @param int $amountInToman Amount of transaction in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string $iban the IBAN (Sheba) number of who you want to pay money to.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction. This can be
     * your factor number.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $name First name of IBAN (Sheba) owner.
     * @param string|null $family Last name of IBAN (Sheba) owner.
     * @return ExtendedPayTransactionResponse Response of Jibimo API in a data model object.
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
        $jibimoPay = new JibimoPay(new Pay(new CurlRequest()));

        $request = new ExtendedPayTransactionRequest($baseUrl, $token, $mobileNumber,
            $amountInToman, $privacy, $iban, $trackerId, $description, $name, $family);

        return $jibimoPay->extendedPay($request);
    }

    /**
     * This method will validate that if a previous request transaction was attempted successfully or not.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param int $transactionId The Jibimo transaction ID of that request transaction that you were made before.
     * @param string $mobileNumber Target mobile number that money was requested from.
     * @param int $amountInToman Amount of that previous transaction in Toomaans.
     * @param string $trackerId Tracker ID of that previous transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function validateRequest(string $baseUrl, string $token, int $transactionId, string $mobileNumber,
                                           int $amountInToman, string $trackerId): JibimoValidationResult
    {
        $jibimoValidator = new JibimoValidator($baseUrl, $token, new Pay(new CurlRequest()),
            new Request(new CurlRequest()));

        return $jibimoValidator->validateRequestTransaction($transactionId, $amountInToman,
            $mobileNumber, $trackerId);
    }

    /**
     * This method will validate that if a previous pay transaction was attempted successfully or not.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param int $transactionId The Jibimo transaction ID of that pay transaction that you were made before.
     * @param string $mobileNumber Target mobile number that money was paid to.
     * @param int $amountInToman Amount of that previous transaction in Toomaans.
     * @param string $trackerId Tracker ID of that previous transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function validatePay(string $baseUrl, string $token, int $transactionId, string $mobileNumber,
                                       int $amountInToman, string $trackerId): JibimoValidationResult
    {
        $jibimoValidator = new JibimoValidator($baseUrl, $token, new Pay(new CurlRequest()),
            new Request(new CurlRequest()));

        return $jibimoValidator->validatePayTransaction($transactionId, $amountInToman, $mobileNumber, $trackerId);
    }

    /**
     * This method will validate that if a previous extended pay transaction was attempted successfully or not.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param int $transactionId The Jibimo transaction ID of that extended pay transaction that you were made before.
     * @param string $mobileNumber Target mobile number that money was paid to.
     * @param int $amountInToman Amount of that previous transaction in Toomaans.
     * @param string $trackerId Tracker ID of that previous transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevelException
     * @throws exceptions\InvalidJibimoResponseException
     * @throws exceptions\InvalidJibimoTransactionStatusException
     * @throws exceptions\InvalidMobileNumberException
     */
    public static function validateExtendedPay(string $baseUrl, string $token, int $transactionId, string $mobileNumber,
                                               int $amountInToman, string $trackerId): JibimoValidationResult
    {
        $jibimoValidator = new JibimoValidator($baseUrl, $token, new Pay(new CurlRequest()),
            new Request(new CurlRequest()));

        return $jibimoValidator->validateExtendedPayTransaction($transactionId, $amountInToman,
            $mobileNumber, $trackerId);
    }
}