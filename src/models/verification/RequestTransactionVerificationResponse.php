<?php


namespace puresoft\jibimo\models\verification;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\models\WithPayer;

class RequestTransactionVerificationResponse extends AbstractTransactionResponse
{
    use WithPayer;

    /**
     * RequestTransactionVerificationResponse constructor.
     * @param string $raw
     * @param int $transactionId
     * @param string $trackerId
     * @param int $amount
     * @param string $payer
     * @param string $privacy
     * @param string $status
     * @param string $createdAt
     * @param string $updatedAt
     * @param string|null $description
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer,
                                string $privacy, string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $privacy, $status, $createdAt,
            $updatedAt, $description);

        $this->setPayer($payer);
    }

}