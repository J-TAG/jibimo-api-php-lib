<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\internals\CurlResult;

class JibimoPay
{
    private $baseUrl;
    private $token;

    /**
     * JibimoPay constructor.
     * @param string $token Jibimo API token.
     * @param string $baseUrl URL of Jibimo API.
     */
    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    /**
     * Using this method you can perform a Jibimo pay money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param string $mobileNumber Target mobile number to pay money to.
     * @param int $amount Amount of money to pay in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @return CurlResult CURL execution result.
     * @throws exceptions\CurlResultFailedException
     */
    public function pay(string $mobileNumber, int $amount, string $privacy, string $trackerId,
                        ?string $description = null)
    {
        return Pay::pay($this->baseUrl, $this->token, $mobileNumber, $amount, $privacy, $trackerId, $description);
    }

    /**
     * After creating a pay transaction. You will need to check the status of that transaction using this method.
     * @param int $transactionId The ID of a pay money transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws exceptions\CurlResultFailedException
     */
    public function validatePay(int $transactionId)
    {
        return Pay::validatePay($this->baseUrl, $this->token, $transactionId);
    }

    /**
     * Using this method you can perform a Jibimo extended pay money transaction to a mobile number and an IBAN which
     * its owner may or may not be in Jibimo.
     * @param string $mobileNumber Target mobile number to pay money to.
     * @param int $amount Amount of money to pay in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $iban The IBAN (Sheba) number of that bank account which you want to transfer money to. Without
     * leading `IR`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $name The first name of the person whom you want to pay to.
     * @param string|null $family The last name of the person whom you want to pay to.
     * @return CurlResult CURL execution result.
     * @throws exceptions\CurlResultFailedException
     */
    public function extendedPay(string $mobileNumber, int $amount, string $privacy, string $iban, string $trackerId,
                                ?string $description = null, ?string $name = null,
                                ?string $family = null)
    {
        return Pay::extendedPay($this->baseUrl, $this->token, $mobileNumber, $amount, $privacy, $iban, $trackerId,
            $description, $name, $family);
    }

    /**
     * After creating an extended pay transaction. You will need to check the status of that transaction using this
     * method.
     * @param int $transactionId The ID of an extended pay money transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws exceptions\CurlResultFailedException
     */
    public function extendedPayValidate(int $transactionId)
    {
        return Pay::validateExtendedPay($this->baseUrl, $this->token, $transactionId);
    }
}