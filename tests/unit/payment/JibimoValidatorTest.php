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
use puresoft\jibimo\models\verification\ExtendedPayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\PayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\RequestTransactionVerificationResponse;
use puresoft\jibimo\payment\JibimoPay;
use puresoft\jibimo\payment\JibimoValidator;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoValidatorTest extends TestCase
{
    /**
     * This method will test validation of a request transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testValidationOfRequestTransaction(): void
    {
        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token']);

        $validationResult = $jibimoValidator->validateRequestTransaction(2421, 85000,
            "+989366061280", "85");

        /** @var $verificationResponse RequestTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayer());
    }

    /**
     * This method will test validation of a pay transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidJibimoResponseException
     */
    public function testValidationOfPayTransaction(): void
    {
        // First create a pay transaction

        $jibimoPay = new JibimoPay();


        $request = new PayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");


        $response = $jibimoPay->pay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Pay transaction

        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token']);

        $validationResult = $jibimoValidator->validatePayTransaction($response->getTransactionId(), 8500,
            "+989366061280", "85");

        /** @var $verificationResponse PayTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayee());
    }

    /**
     * This method will test validation of an extended pay transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidIbanException
     */
    public function testValidationOfExtendedPayTransaction(): void
    {
        // First create an extended pay transaction

        $jibimoPay = new JibimoPay();


        $request = new ExtendedPayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");


        $response = $jibimoPay->extendedPay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Extended Pay transaction

        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token']);

        $validationResult = $jibimoValidator->validateExtendedPayTransaction($response->getTransactionId(), 8500,
            "+989366061280", "85");

        /** @var $verificationResponse ExtendedPayTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayee());
    }
}