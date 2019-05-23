<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

abstract class AbstractTransactionResponse
{
    private $raw;

    private $transactionId;
    private $trackerId;
    private $amount;
    private $privacy;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $description;

    /**
     * AbstractTransactionResponse constructor.
     * @param string $raw
     * @param int $transactionId
     * @param string $trackerId
     * @param int $amount
     * @param string $privacy
     * @param string $status
     * @param string $createdAt
     * @param string $updatedAt
     * @param string|null $description
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoTransactionStatus
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $privacy,
                                string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        $this->raw = $raw;
        $this->transactionId = $transactionId;
        $this->trackerId = $trackerId;
        $this->amount = $amount;
        $this->privacy = DataNormalizer::normalizePrivacyLevel($privacy);
        $this->status = DataNormalizer::normalizeTransactionStatus($status);
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
     * @throws InvalidJibimoPrivacyLevel
     */
    public function getPrivacy(): string
    {
        return DataNormalizer::normalizePrivacyLevel($this->privacy);
    }

    /**
     * @return string
     * @throws InvalidJibimoTransactionStatus
     */
    public function getStatus(): string
    {
        return DataNormalizer::normalizeTransactionStatus($this->status);
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