<?php

declare(strict_types=1);

namespace unit;

use PHPUnit\Framework\TestCase;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\Jibimo;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoTest extends TestCase
{
    /**
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanRequestCharge(): void
    {
        $response = Jibimo::request($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            850000, JibimoPrivacyLevel::PERSONAL, "85");

        $this->assertEquals(JibimoTransactionStatus::PENDING, $response->getStatus());
        $this->assertNotNull($response->getRedirectUrl());
    }

    /**
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanPayMoney(): void
    {
        $response = Jibimo::pay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());
    }

    /**
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidIbanException
     */
    public function testCanExtendedPayMoneyUsingIban(): void
    {
        $response = Jibimo::extendedPay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());
    }
}