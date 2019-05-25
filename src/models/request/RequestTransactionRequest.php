<?php


namespace puresoft\jibimo\models\request;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\AbstractTransactionRequest;

class RequestTransactionRequest extends AbstractTransactionRequest
{
    private $returnUrl;

    /**
     * RequestTransactionRequest constructor.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number to request money from.
     * @param int $amount Amount of money to request in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $returnUrl The URL to return after payment. If you leave this URL blank, Jibimo will redirect
     * user to your company homepage.
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $trackerId, ?string $description = null, ?string $returnUrl = null)
    {
        parent::__construct($baseUrl, $token, $mobileNumber, $amount, $privacy, $trackerId, $description);


        $this->returnUrl = $returnUrl;
    }

    /**
     * Returns the return URL which you are provided to return back to it after finishing transaction.
     * @return string|null Your provided return URL or `null` if it's not presented by you.
     */
    public function getReturnUrl(): ?string
    {
        return $this->returnUrl;
    }

}