<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;

class PayTransactionRequest extends AbstractTransactionRequest
{

    /**
     * PayTransactionRequest constructor.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number to pay money to.
     * @param int $amount Amount of money to pay in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $trackerId, ?string $description = null)
    {
        parent::__construct($baseUrl, $token, $mobileNumber, $amount, $privacy, $trackerId, $description);
    }

}