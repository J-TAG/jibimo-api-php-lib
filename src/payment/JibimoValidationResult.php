<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\verification\TransactionVerificationResponse;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoValidationResult
{
    private $curlResult;
    private $isValid;
    private $response;

    /**
     * JibimoValidationResult constructor.
     * @param CurlResult $curlResult
     * @param bool $isValid
     * @param TransactionVerificationResponse|null $response
     */
    public function __construct(CurlResult $curlResult, bool $isValid, ?TransactionVerificationResponse $response = null)
    {
        $this->curlResult = $curlResult;
        $this->isValid = $isValid;

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
     * @throws InvalidJibimoTransactionStatus
     */
    public function isAccepted(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::ACCEPTED);
    }

    /**
     * @return bool
     * @throws InvalidJibimoTransactionStatus
     */
    public function isPending(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::PENDING);
    }

    /**
     * @return bool
     * @throws InvalidJibimoTransactionStatus
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
        return $this->curlResult->getResult();
    }

    /**
     * @return CurlResult
     */
    public function getCurlResult(): CurlResult
    {
        return $this->curlResult;
    }

    /**
     * @return string|null
     * @throws InvalidJibimoTransactionStatus
     */
    public function getStatus(): ?string
    {
        if(empty($this->response)) {
            return null;
        }
        
        return $this->getResponse()->getStatus();
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