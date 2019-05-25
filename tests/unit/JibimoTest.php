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
use puresoft\jibimo\models\verification\ExtendedPayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\PayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\RequestTransactionVerificationResponse;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoTest extends TestCase
{
    /**
     * This method will test a normal request transaction in Jibimo factory class.
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
     * This method will test a normal pay transaction in Jibimo factory class.
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
     * This method will test an extended pay transaction.
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

    /**
     * This method will test validation of a normal request transaction in Jibimo factory class.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testRequestTransactionValidation(): void
    {
        $validationResult = Jibimo::validateRequest($GLOBALS['baseUrl'], $GLOBALS['token'], 2421,
            "+989366061280",85000, "85");

        /** @var $verificationResponse RequestTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayer());
    }

    /**
     * This method will test validation of a normal pay transaction in Jibimo factory class.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testPayTransactionValidation(): void
    {
        // First create a pay transaction

        $response = Jibimo::pay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Pay transaction

        $validationResult = Jibimo::validatePay($GLOBALS['baseUrl'], $GLOBALS['token'], $response->getTransactionId(),
            "+989366061280", 8500, "85");

        /** @var $verificationResponse PayTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayee());
    }

    /**
     * This method will test validation of an extended pay transaction in Jibimo factory class.
     * @throws CurlResultFailedException
     * @throws InvalidIbanException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testExtendedPayTransactionValidation(): void
    {
        // First create an extended pay transaction

        $response = Jibimo::extendedPay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Extended Pay transaction

        $validationResult = Jibimo::validateExtendedPay($GLOBALS['baseUrl'], $GLOBALS['token'],
            $response->getTransactionId(), "+989366061280",8500, "85");

        /** @var $verificationResponse ExtendedPayTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayee());
    }
}