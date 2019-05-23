<?php


namespace puresoft\jibimo;


use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\internals\DataNormalizer;
use puresoft\jibimo\models\TransactionVerificationResponse;

class JibimoValidationResult
{
    private $raw;
    private $isValid;
    private $status;
    private $response;

    /**
     * JibimoValidationResult constructor.
     * @param string $raw
     * @param bool $isValid
     * @param string|null $status
     * @param TransactionVerificationResponse|null $response
     * @throws InvalidJibimoTransactionStatus
     */
    public function __construct(string $raw, bool $isValid, ?string $status = null, ?TransactionVerificationResponse $response = null)
    {
        $this->raw = $raw;
        $this->isValid = $isValid;

        if(isset($status)) {
            $this->status = DataNormalizer::normalizeTransactionStatus($status);
        }

        if(isset($response)) {
            $this->response = $response;
        }
    }

    /**
     * @return array|null
     */
    public function toArray(): ?array
    {
        return json_decode($this->getRawResponse(), true);
    }

    /**
     * @return mixed
     */
    public function toObject()
    {
        return json_decode($this->getRawResponse());
    }

    /**
     * @return bool
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function isAccepted(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::ACCEPTED);
    }

    /**
     * @return bool
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function isPending(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::PENDING);
    }

    /**
     * @return bool
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function isRejected(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::REJECTED);
    }

    /**
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->raw;
    }

    /**
     * @return string|null
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function getStatus(): ?string
    {
        if(isset($this->status)) {
            return DataNormalizer::normalizeTransactionStatus($this->status);
        }

        return null;
    }

    /**
     * @return TransactionVerificationResponse|null
     */
    public function getResponse(): ?TransactionVerificationResponse
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param string $status
     * @return bool
     * @throws InvalidJibimoTransactionStatus
     */
    private function safeStatusCheck(string $status): bool {
        return ($this->isValid() and $this->getStatus() === $status);
    }

}