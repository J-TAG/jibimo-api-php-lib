<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\api\Request;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\verification\TransactionVerificationResponse;

class JibimoValidator
{
    private $baseUrl;
    private $token;

    /**
     * JibimoValidator constructor.
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }


    /**
     * After creating a request transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a money request transaction that you requested before.
     * @param int $amount
     * @param string $mobileNumber
     * @param string $trackerId
     * @return JibimoValidationResult
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoTransactionStatus
     * @throws InvalidMobileNumberException
     */
    public function validateRequestTransaction(int $transactionId, int $amount, string $mobileNumber, string $trackerId)
        : JibimoValidationResult
    {
        $curlResult = Request::validateRequest($this->baseUrl, $this->token, $transactionId);

        $rawResult = $curlResult->getResult();

        $jsonResult = json_decode($rawResult);


        if(empty($jsonResult->id) or $curlResult->getHttpStatusCode() !== 200) {

            // Response is not a transaction
            return new JibimoValidationResult($curlResult, false);

        }

        $response = new TransactionVerificationResponse($rawResult, $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payer, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);


        if($response->getTransactionId() === $transactionId and $response->getAmount() === $amount
            and $response->getPayer() === $mobileNumber and $response->getTrackerId() === $trackerId) {

            // Transaction details is valid, but its status may be vary

            return new JibimoValidationResult($curlResult, true, $response);

        }

        // Transaction details is invalid, there is not transaction with that details
        return new JibimoValidationResult($curlResult, false);
    }

    /**
     * After creating a pay transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a pay money transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public function validatePay(int $transactionId): JibimoValidationResult
    {
        // TODO: Update code for pay transaction validation
        return Pay::validatePay($this->baseUrl, $this->token, $transactionId);
    }

    /**
     * After creating an extended pay transaction. You will need to check the status of that transaction using this
     * method.
     * @param int $transactionId The ID of an extended pay money transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public function extendedPayValidate(int $transactionId): JibimoValidationResult
    {
        // TODO: Update code for extended pay transaction validation
        return Pay::validateExtendedPay($this->baseUrl, $this->token, $transactionId);
    }
}