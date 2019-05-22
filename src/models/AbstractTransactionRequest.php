<?php


namespace puresoft\jibimo\models;


abstract class AbstractTransactionRequest
{
    private $baseUrl;
    private $token;
    private $mobileNumber;
    private $amount;
    private $privacy;
    private $trackerId;
    private $description;


    /**
     * TransactionRequest constructor.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param string $mobileNumber Target mobile number to request money from.
     * @param int $amount Amount of money to request in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which could be one of `Public`, `Friend` or `Personal`.
     * @param string|null $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     */
    public function __construct(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $trackerId, ?string $description = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->mobileNumber = $mobileNumber;
        $this->amount = $amount;
        $this->privacy = $privacy;
        $this->trackerId = $trackerId;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getMobileNumber(): string
    {
        // TODO: Normalize mobile number here
        return $this->mobileNumber;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getPrivacy(): string
    {
        // TODO: Normalize privacy here
        return $this->privacy;
    }

    /**
     * @return string
     */
    public function getTrackerId(): string
    {
        return $this->trackerId;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}