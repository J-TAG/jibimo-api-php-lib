<?php


namespace puresoft\jibimo\models\request;


use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\AbstractTransactionResponse;
use puresoft\jibimo\models\WithPayer;

class RequestTransactionResponse extends AbstractTransactionResponse
{
    use WithPayer;

    private $redirect;

    /**
     * RequestTransactionResponse constructor.
     * @param string $raw Raw response data.
     * @param int $transactionId Jibimo transaction ID which is unique in Jibimo.
     * @param string $trackerId Tracker ID which is saved in Jibimo and will be used later for finding transaction.
     * @param int $amount Amount of money which is paid by this transaction in Toomaans.
     * @param string $payer Mobile number of person who paid the money.
     * @param string $privacy Jibimo privacy level of transaction which can be one of `Public`, `Friend` or `Personal`.
     * @param string $status Status of transaction in Jibimo which can be one of `Rejected`, `Pending` or `Accepted`.
     * @param string $createdAt Exact date time of creating this transaction.
     * @param string $updatedAt Exact date time that this transaction was modified by someone.
     * @param string $redirect The URL of gateway page in Jibimo. User should be redirected to this page by you.
     * @param string|null $description Descriptions of transaction which will be show up in Jibimo.
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidMobileNumberException
     * @throws InvalidJibimoTransactionStatusException
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
     * Returns a URL from response that is used to redirect user to it in a web page. in this page, user will be viewed
     * Jibimo payment gateway and pay the requested money to you.
     * @return string The gateway URL to redirect user to.
     */
    public function getRedirectUrl(): string
    {
        return $this->redirect;
    }

}