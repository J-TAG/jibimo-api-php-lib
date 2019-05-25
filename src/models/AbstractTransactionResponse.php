<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
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
     * @param string $raw Raw response data.
     * @param int $transactionId Jibimo transaction ID which is unique in Jibimo.
     * @param string $trackerId Tracker ID which is saved in Jibimo and will be used later for finding transaction.
     * @param int $amount Amount of money which is paid by this transaction in Toomaans.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $status Status of transaction in Jibimo which can be one of `Rejected`, `Pending` or `Accepted`.
     * @param string $createdAt Exact date time of creating this transaction.
     * @param string $updatedAt Exact date time that this transaction was modified by someone.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
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
     * Returns the raw response body,
     * @return string Raw response string.
     */
    public function getRawResponse(): string
    {
        return $this->raw;
    }

    /**
     * Returns the transaction ID which is unique in Jibimo.
     * @return int The transaction ID.
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * Returns the tracker ID that you are provided for this transaction.
     * @return string Your provided tracker ID.
     */
    public function getTrackerId(): string
    {
        return $this->trackerId;
    }

    /**
     * Returns the amount of this transaction in Toomaans.
     * @return int The amount in Toomaans.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Return the Jibimo privacy level of this transaction.
     * @return string Privacy level which can be one of `Personal`, `Friend`, or`Public`.
     * @throws InvalidJibimoPrivacyLevelException
     */
    public function getPrivacy(): string
    {
        return DataNormalizer::normalizePrivacyLevel($this->privacy);
    }

    /**
     * Returns the status of this transaction.
     * @return string Transaction status which can be one of `Rejected`, `Pending` or `Accepted`.
     * @throws InvalidJibimoTransactionStatusException
     */
    public function getStatus(): string
    {
        return DataNormalizer::normalizeTransactionStatus($this->status);
    }

    /**
     * Returns the creation time of this transaction in Jibimo.
     * @return string The creation time string.
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Returns the last update time of this transaction in Jibimo.
     * @return string The update time string.
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * Returns the description of this transaction that you are provided.
     * @return string|null The description text if it was provided by you.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}