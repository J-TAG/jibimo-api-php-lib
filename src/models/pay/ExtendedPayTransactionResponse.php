<?php


namespace puresoft\jibimo\models\pay;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;

class ExtendedPayTransactionResponse extends PayTransactionResponse
{

    /**
     * ExtendedPayTransactionResponse constructor.
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
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
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