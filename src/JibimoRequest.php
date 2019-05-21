<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Request;

class JibimoRequest
{
    private $token;
    private $baseUrl;

    /**
     * JibimoRequest constructor.
     * @param string $token Jibimo API token.
     * @param string $baseUrl URL of Jibimo API.
     */
    public function __construct(string $token, string $baseUrl)
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Using this method you can perform a Jibimo request money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param string $mobileNumber Target mobile number to request money from.
     * @param int $amount Amount of money to request in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction. This value
     * is optional, but it is highly recommended to provide a unique value for it.
     * @param string|null $returnUrl The URL to return after payment. If you leave this URL blank, Jibimo will redirect
     * user to your company homepage.
     * @return bool|string CURL execution result.
     */
    public function request(string $mobileNumber, int $amount, string $privacy, ?string $description, ?string $trackerId,
                            ?string $returnUrl)
    {
        return Request::request($this->baseUrl, $this->token, $mobileNumber, $amount, $privacy, $description, $trackerId, $returnUrl);
    }

    /**
     * After creating a request transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a money request transaction that you requested before.
     * @return bool|string CURL execution result.
     */
    public function validate(int $transactionId)
    {
        return Request::validateRequest($this->baseUrl, $this->token, $transactionId);
    }
}