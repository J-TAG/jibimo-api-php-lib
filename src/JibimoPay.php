<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Pay;

class JibimoPay
{
    private $token;
    private $baseUrl;

    /**
     * JibimoPay constructor.
     * @param string $token Jibimo API token.
     * @param string $baseUrl URL of Jibimo API.
     */
    public function __construct(string $token, string $baseUrl)
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Using this method you can perform a Jibimo pay money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param string $mobileNumber Target mobile number to pay money to.
     * @param int $amount Amount of money to pay in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction. This value
     * is optional, but it is highly recommended to provide a unique value for it.
     * @return bool|string CURL execution result.
     */
    public function pay(string $mobileNumber, int $amount, string $privacy, ?string $description, ?string $trackerId)
    {
        return Pay::pay($this->baseUrl, $this->token, $mobileNumber, $amount, $privacy, $description, $trackerId);
    }

    /**
     * After creating a pay transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a pay money transaction that you requested before.
     * @return bool|string CURL execution result.
     */
    public function validate(int $transactionId)
    {
        return Pay::validatePay($this->baseUrl, $this->token, $transactionId);
    }
}