<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoValidationResult
{
    private $curlResult;
    private $isValid;
    private $response;

    /**
     * JibimoValidationResult constructor.
     * @param CurlResult $curlResult CURL result set.
     * @param bool $isValid Whether the validation was successful or not.
     * @param AbstractTransactionResponse|null $response Original response data object.
     */
    public function __construct(CurlResult $curlResult, bool $isValid, ?AbstractTransactionResponse $response = null)
    {
        $this->curlResult = $curlResult;
        $this->isValid = $isValid;

        if(isset($response)) {
            $this->response = $response;
        }
    }

    /**
     * Converts CURL result set to array.
     * @return array|null An array of values or null if ot fails.
     */
    public function toArray(): ?array
    {
        return json_decode($this->getRawResponse(), true);
    }

    /**
     * Converts CURL result set to object.
     * @return mixed An object which is relevant to JSON result or null if failed.
     */
    public function toObject()
    {
        return json_decode($this->getRawResponse());
    }

    /**
     * Checks to see if this transaction is valid in Jibimo and the status of it was `Accepted` or not.
     * @return bool Result of validation.
     * @throws InvalidJibimoTransactionStatusException
     */
    public function isAccepted(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::ACCEPTED);
    }

    /**
     * Checks to see if this transaction is valid in jibimo and the status of it was `Pending` or not.
     * @return bool Result of validation.
     * @throws InvalidJibimoTransactionStatusException
     */
    public function isPending(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::PENDING);
    }

    /**
     * Checks to see if this transaction is valid in jibimo and the status of it was `Rejected` or not.
     * @return bool Result of validation.
     * @throws InvalidJibimoTransactionStatusException
     */
    public function isRejected(): bool
    {
        return $this->safeStatusCheck(JibimoTransactionStatus::REJECTED);
    }

    /**
     * Returns raw response body.
     * @return string Response string.
     */
    public function getRawResponse(): string
    {
        return $this->curlResult->getResult();
    }

    /**
     * Returns CURL result set object.
     * @return CurlResult
     */
    public function getCurlResult(): CurlResult
    {
        return $this->curlResult;
    }

    /**
     * Returns the status of this transaction Jibimo which may be one of `Rejected`, `Pending` or `Accepted`.
     * @return string|null The status string.
     * @throws InvalidJibimoTransactionStatusException
     */
    public function getStatus(): ?string
    {
        if(empty($this->response)) {
            return null;
        }
        
        return $this->getResponse()->getStatus();
    }

    /**
     * Returns the response data object of validation.
     * @return AbstractTransactionResponse|null Response data object or null if it's not presented.
     */
    public function getResponse(): ?AbstractTransactionResponse
    {
        return $this->response;
    }

    /**
     * Returns true if data of this transaction is valid in Jibimo, otherwise returns false. But please keep in mind,
     * that this is not mean that transaction was successful. To check that you should use `isAccepted` method.
     * @return bool Whether the transaction is valid in Jibimo.
     * @see JibimoValidationResult::isAccepted()
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Checks the status of this object only after successful validation.
     * @param string $status Jibimo status to check.
     * @return bool Whether the status matches after validation or not.
     * @throws InvalidJibimoTransactionStatusException
     */
    private function safeStatusCheck(string $status): bool {
        return ($this->isValid() and $this->getStatus() === $status);
    }

}