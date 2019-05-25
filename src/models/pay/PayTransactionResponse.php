<?php


namespace puresoft\jibimo\models\pay;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\models\WithPayee;

class PayTransactionResponse extends AbstractTransactionResponse
{
    use WithPayee;


    /**
     * PayTransactionResponse constructor.
     * @param string $raw Raw response data.
     * @param int $transactionId Jibimo transaction ID which is unique in Jibimo.
     * @param string $trackerId Tracker ID which is saved in Jibimo and will be used later for finding transaction.
     * @param int $amount Amount of money which is paid by this transaction in Toomaans.
     * @param string $payee Mobile number of person who received the money.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $status Status of transaction in Jibimo which can be one of `Rejected`, `Pending` or `Accepted`.
     * @param string $createdAt Exact date time of creating this transaction.
     * @param string $updatedAt Exact date time that this transaction was modified by someone.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payee,
                                string $privacy, string $status, string $createdAt, string $updatedAt,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $privacy, $status, $createdAt,
            $updatedAt, $description);

        $this->setPayee($payee);
    }

}