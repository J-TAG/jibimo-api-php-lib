<?php


namespace puresoft\jibimo\models\request;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\models\WithPayer;

class RequestTransactionResponse extends AbstractTransactionResponse
{
    use WithPayer;

    private $redirect;

    /**
     * RequestTransactionResponse constructor.
     * @param string $raw
     * @param int $transactionId
     * @param string $trackerId
     * @param int $amount
     * @param string $payer
     * @param string $privacy
     * @param string $status
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $redirect
     * @param string|null $description
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidMobileNumberException
     * @throws InvalidJibimoTransactionStatus
     */
    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer,
                                string $privacy, string $status, string $createdAt, string $updatedAt, string $redirect,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $privacy, $status, $createdAt,
            $updatedAt, $description);

        $this->redirect = $redirect;
        $this->setPayer($payer);
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirect;
    }

}