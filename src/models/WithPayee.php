<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

trait WithPayee
{
    private $payee;

    /**
     * @param string $payee
     * @return void
     * @throws InvalidMobileNumberException
     */
    public function setPayee(string $payee): void
    {
        $this->payee = DataNormalizer::normalizeMobileNumber($payee);
    }

    /**
     * @return string
     * @throws InvalidMobileNumberException
     */
    public function getPayee(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->payee);
    }
}