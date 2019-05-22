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
     * @return bool Transaction validation result.
     * @throws exceptions\CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevel
     * @throws exceptions\InvalidMobileNumberException
     */
    public function validateRequestTransaction(int $transactionId, int $amount, string $mobileNumber): bool
    {
        $curlResult = Request::validateRequest($this->baseUrl, $this->token, $transactionId);

        // TODO : Check API errors and http status code here

        $rawResult = $curlResult->getResult();

        $jsonResult = json_decode($rawResult);

        $response = new TransactionVerificationResponse($rawResult, $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payer, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);

        var_dump($response);

        // TODO: Check response here, status must be Accepted
        return false;
    }
}