<?php

declare(strict_types=1);

namespace unit\payment;


use PHPUnit\Framework\TestCase;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\pay\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\pay\PayTransactionRequest;
use puresoft\jibimo\payment\JibimoPay;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoPayTest extends TestCase
{
    /**
     * This method will test a normal pay transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanDoNormalPay(): void
    {
        $jibimoPay = new JibimoPay();


        $request = new PayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");


        $response = $jibimoPay->pay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());
    }

    /**
     * This method will test an extended pay transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidIbanException
     */
    public function testCanDoExtendedPay(): void
    {
        $jibimoPay = new JibimoPay();


        $request = new ExtendedPayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");


        $response = $jibimoPay->extendedPay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());
    }
}