<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\api\Request;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\models\verification\ExtendedPayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\PayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\RequestTransactionVerificationResponse;

class JibimoValidator extends AbstractTransactionProvider
{
    private $baseUrl;
    private $token;

    /**
     * JibimoValidator constructor.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     */
    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    /**
     * After creating a request transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a money request transaction that you requested before.
     * @param int $amount Amount of transaction in Toomaans.
     * @param string $mobileNumber Target mobile number that money was requested from.
     * @param string $trackerId Tracker ID of that transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidJibimoResponseException
     */
    public function validateRequestTransaction(int $transactionId, int $amount, string $mobileNumber, string $trackerId)
        : JibimoValidationResult
    {
        $curlResult = Request::validateRequest($this->baseUrl, $this->token, $transactionId);

        $jsonResult = $this->convertRawDataToJson($curlResult);

        $response = new RequestTransactionVerificationResponse($curlResult->getResult(), $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payer, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);


        return $this->validateAndReturnResult($curlResult, $response, $transactionId, $amount, $response->getPayer(),
            $mobileNumber, $trackerId);
    }

    /**
     * After creating a pay transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a pay money transaction that you requested before.
     * @param int $amount Amount of transaction in Toomaans.
     * @param string $mobileNumber Target mobile number that money was paid to.
     * @param string $trackerId Tracker ID of that transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function validatePayTransaction(int $transactionId, int $amount, string $mobileNumber, string $trackerId)
    : JibimoValidationResult
    {
        $curlResult = Pay::validatePay($this->baseUrl, $this->token, $transactionId);

        $jsonResult = $this->convertRawDataToJson($curlResult);

        $response = new PayTransactionVerificationResponse($curlResult->getResult(), $jsonResult->id,
            $jsonResult->tracker_id, $jsonResult->amount, $jsonResult->payee, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);


        return $this->validateAndReturnResult($curlResult, $response, $transactionId, $amount, $response->getPayee(),
            $mobileNumber, $trackerId);
    }

    /**
     * After creating an extended pay transaction. You will need to check the status of that transaction using this
     * method.
     * @param int $transactionId The ID of an extended pay money transaction that you requested before.
     * @param int $amount Amount of transaction in Toomaans.
     * @param string $mobileNumber Target mobile number that money was paid to.
     * @param string $trackerId Tracker ID of that transaction.
     * @return JibimoValidationResult Validation result object.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function validateExtendedPayTransaction(int $transactionId, int $amount, string $mobileNumber,
                                                   string $trackerId): JibimoValidationResult
    {
        $curlResult = Pay::validateExtendedPay($this->baseUrl, $this->token, $transactionId);

        $jsonResult = $this->convertRawDataToJson($curlResult);

        $response = new ExtendedPayTransactionVerificationResponse($curlResult->getResult(), $jsonResult->id,
            $jsonResult->tracker_id, $jsonResult->amount, $jsonResult->payee, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);

        return $this->validateAndReturnResult($curlResult, $response, $transactionId, $amount, $response->getPayee(),
            $mobileNumber, $trackerId);

    }

    /**
     * This method will do some general checks and returns the validation result object based on input data.
     * @param CurlResult $curlResult CURL result set object.
     * @param AbstractTransactionResponse $response Validation API response data object.
     * @param int $transactionId The ID of that transaction.
     * @param int $amount Amount of transaction in Toomaans.
     * @param string $expectedMobileNumber The mobile number that developer expects money was paid to.
     * @param string $mobileNumber Target mobile number that money was paid to.
     * @param string $trackerId Tracker ID of that transaction.
     * @return JibimoValidationResult Generated validation result data object.
     */
    private function validateAndReturnResult(CurlResult $curlResult, AbstractTransactionResponse $response, int $transactionId, int $amount,
                                             string $expectedMobileNumber, string $mobileNumber, string $trackerId)
    : JibimoValidationResult
    {
        if($response->getTransactionId() === $transactionId and $response->getAmount() === $amount
            and $expectedMobileNumber === $mobileNumber and $response->getTrackerId() === $trackerId) {

            // Transaction details is valid, but its status may be vary
            return new JibimoValidationResult($curlResult, true, $response);

        }

        // Transaction details is invalid, there is no transaction with that details
        return new JibimoValidationResult($curlResult, false);
    }
}