<?php


namespace puresoft\jibimo\models\verification;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;

class ExtendedPayTransactionVerificationResponse extends PayTransactionVerificationResponse
{
    /**
     * ExtendedPayTransactionVerificationResponse constructor.
     * @param string $raw
     * @param int $transactionId
     * @param string $trackerId
     * @param int $amount
     * @param string $payee
     * @param string $privacy
     * @param string $status
     * @param string $createdAt
     * @param string $updatedAt
     * @param string|null $description
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoTransactionStatus
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payee,
                                string $privacy, string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $payee, $privacy, $status, $createdAt,
            $updatedAt, $description);
    }
}