<?php


namespace puresoft\jibimo\models;


use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\DataNormalizer;

trait WithPayer
{
    private $payer;

    /**
     * @param string $payer
     * @return void
     * @throws InvalidMobileNumberException
     */
    public function setPayer(string $payer): void
    {
        $this->payer = DataNormalizer::normalizeMobileNumber($payer);
    }

    /**
     * @return string
     * @throws InvalidMobileNumberException
     */
    public function getPayer(): string
    {
        return DataNormalizer::normalizeMobileNumber($this->payer);
    }
}