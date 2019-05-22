<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

abstract class AbstractTransactionResponse
{
    private $raw;

    private $transactionId;
    private $trackerId;
    private $amount;
    private $payer;
    private $privacy;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $description;

    /**
     * AbstractTransactionResponse constructor.
     * @param $raw
     * @param $transactionId
     * @param $trackerId
     * @param $amount
     * @param $payer
     * @param $privacy
     * @param $status
     * @param $createdAt
     * @param $updatedAt
     * @param $description
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer,
                                string $privacy, string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        $this->raw = $raw;
        $this->transactionId = $transactionId;
        $this->trackerId = $trackerId;
        $this->amount = $amount;
        $this->payer = DataNormalizer::normalizeMobileNumber($payer);
        $this->privacy = DataNormalizer::normalizePrivacyLevel($privacy);
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->raw;
    }

    /**
     * @return int
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getTrackerId(): string
    {
        return $this->trackerId;
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
     * @throws InvalidMobileNumberException
     */
    public function getPayer(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->payer);
    }

    /**
     * @return string
     * @throws InvalidJibimoPrivacyLevel
     */
    public function getPrivacy(): string
    {
        return DataNormalizer::normalizePrivacyLevel($this->privacy);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        // TODO: Normalize status here
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}