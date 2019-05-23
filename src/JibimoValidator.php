<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Request;
use puresoft\jibimo\models\TransactionVerificationResponse;

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
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevel
     * @throws exceptions\InvalidMobileNumberException
     */
    public function validateRequestTransaction(int $transactionId, int $amount, string $mobileNumber, string $trackerId)
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
}