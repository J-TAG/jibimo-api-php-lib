<?php

declare(strict_types=1);

namespace unit\payment;


use PHPUnit\Framework\TestCase;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\payment\JibimoValidator;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoValidatorTest extends TestCase
{
    /**
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoTransactionStatus
     * @throws InvalidMobileNumberException
     */
    public function testValidationOfRequestTransaction(): void
    {
        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token']);

        $validationResult = $jibimoValidator->validateRequestTransaction(2421, 85000,
            "+989366061280", "85");

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $validationResult->getResponse()->getPayer());
    }

    // TODO: Add test for pay and extended pay validation
}