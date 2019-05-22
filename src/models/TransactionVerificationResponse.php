<?php


namespace puresoft\jibimo\models;


class TransactionVerificationResponse extends AbstractTransactionResponse
{

    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer,
                                string $privacy, string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $payer, $privacy, $status, $createdAt,
            $updatedAt, $description);
    }

}