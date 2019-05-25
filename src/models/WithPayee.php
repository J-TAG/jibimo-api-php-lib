<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

trait WithPayee
{
    private $payee;

    /**
     * Set payee mobile number.
     * @param string $payee The mobile number of payee.
     * @return void
     * @throws InvalidMobileNumberException
     */
    public function setPayee(string $payee): void
    {
        $this->payee = DataNormalizer::normalizeMobileNumber($payee);
    }

    /**
     * Returns the mobile number of who this transaction was paid to.
     * @return string The mobile number string.
     * @throws InvalidMobileNumberException
     */
    public function getPayee(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->payee);
    }
}