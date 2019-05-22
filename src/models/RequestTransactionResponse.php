<?php


namespace puresoft\jibimo\models;


class RequestTransactionResponse extends AbstractTransactionResponse
{

    private $redirect;

    public function __construct(string $raw, int $transactionId, string $trackerId, int $amount, string $payer,
                                string $privacy, string $status, string $createdAt, string $updatedAt, string $redirect,
                                ?string $description = null)
    {
        parent::__construct($raw, $transactionId, $trackerId, $amount, $payer, $privacy, $status, $createdAt,
            $updatedAt, $description);

        $this->redirect = $redirect;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirect;
    }

}