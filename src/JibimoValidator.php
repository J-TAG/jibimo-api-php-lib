<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Request;

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
     */
    public function validateRequestTransaction(int $transactionId, int $amount, string $mobileNumber): bool
    {
        $response = Request::validateRequest($this->baseUrl, $this->token, $transactionId);

        // TODO: Check response here
        return false;
    }
}