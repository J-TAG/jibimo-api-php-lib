<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;

class ExtendedPayTransactionRequest extends PayTransactionRequest
{
    private $iban;
    private $name;
    private $family;

    /**
     * ExtendedPayTransactionRequest constructor.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number to pay money to.
     * @param int $amount Amount of money to pay in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $iban The IBAN (Sheba) number of that bank account which you want to transfer money to. Without
     * leading `IR`.
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @param string|null $name The first name of the person whom you want to pay to.
     * @param string|null $family The last name of the person whom you want to pay to.
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $iban, string $trackerId, ?string $description = null, ?string $name = null,
                                ?string $family = null)
    {
        parent::__construct($baseUrl, $token, $mobileNumber, $amount, $privacy, $trackerId, $description);

        // TODO: Normalize IBAN here
        $this->iban = $iban;
        $this->name = $name;
        $this->family = $family;

    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        // TODO: Normalize IBAN here
        return $this->iban;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getFamily(): ?string
    {
        return $this->family;
    }
}