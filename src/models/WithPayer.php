<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

trait WithPayer
{
    private $payer;

    /**
     * Set payer mobile number.
     * @param string $payer The mobile number of payer.
     * @return void
     * @throws InvalidMobileNumberException
     */
    public function setPayer(string $payer): void
    {
        $this->payer = DataNormalizer::normalizeMobileNumber($payer);
    }

    /**
     * Returns the mobile number of whom this transaction was charged.
     * @return string The mobile number string.
     * @throws InvalidMobileNumberException
     */
    public function getPayer(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->payer);
    }
}