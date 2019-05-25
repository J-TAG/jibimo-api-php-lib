<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

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
     * @param string $trackerId Tracker ID to be saved in Jibimo and used later for finding transaction.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                string $trackerId, ?string $description = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->mobileNumber = DataNormalizer::normalizeMobileNumber($mobileNumber);
        $this->amount = $amount;
        $this->privacy = DataNormalizer::normalizePrivacyLevel($privacy);
        $this->trackerId = $trackerId;
        $this->description = $description;
    }

    /**
     * Returns associated base URL with this request.
     * @return string The base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Returns associated token with this request.
     * @return string The API token.
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Returns associated mobile number with this request.
     * @return string The mobile number.
     * @throws InvalidMobileNumberException
     */
    public function getMobileNumber(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->mobileNumber);
    }

    /**
     * Returns amount of this transaction in Toomaans.
     * @return int Amount in Toomaans.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Returns the privacy scope of this transaction.
     * @return string The privacy of this transaction which will be one of `Personal`, `Friend` or `Public`.
     * @throws InvalidJibimoPrivacyLevelException
     */
    public function getPrivacy(): string
    {
        return DataNormalizer::normalizePrivacyLevel($this->privacy);
    }

    /**
     * Returns associated tracker ID to this transaction which is provided by you and can be used later to identify the
     * transaction.
     * @return string The tracker ID that is provided by you.
     */
    public function getTrackerId(): string
    {
        return $this->trackerId;
    }

    /**
     * Returns the description of this transaction that you are set.
     * @return string|null The description of this transaction if provided by you.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}