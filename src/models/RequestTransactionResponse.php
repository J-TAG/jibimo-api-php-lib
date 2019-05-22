<?php


namespace puresoft\jibimo\models;


class RequestTransactionResponse extends AbstractTransactionResponse
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
    private $redirect;
    private $description;

    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer, string $privacy,
                                string $status, string $createdAt, string $updatedAt, string $redirect,
                                ?string $description = null)
    {
        $this->raw = $raw;
        $this->transactionId = $transactionId;
        $this->trackerId = $trackerId;
        $this->amount = $amount;
        $this->payer = $payer;
        $this->privacy = $privacy;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->redirect = $redirect;
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
     */
    public function getPayer(): string
    {
        return $this->payer;
    }

    /**
     * @return string
     */
    public function getPrivacy(): string
    {
        return $this->privacy;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
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
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirect;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}