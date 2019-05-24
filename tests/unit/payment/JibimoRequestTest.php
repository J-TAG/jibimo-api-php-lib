<?php

declare(strict_types=1);


namespace unit\payment;


use PHPUnit\Framework\TestCase;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoResponse;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\request\RequestTransactionRequest;
use puresoft\jibimo\payment\JibimoRequest;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoRequestTest extends TestCase
{
    /**
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoResponse
     * @throws InvalidJibimoTransactionStatus
     * @throws InvalidMobileNumberException
     */
    public function testCanRequestMoney()
    {

        $jibimoRequest = new JibimoRequest();


        $request = new RequestTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            850000, JibimoPrivacyLevel::PERSONAL, "85");


        $response = $jibimoRequest->request($request);

        $this->assertEquals(JibimoTransactionStatus::PENDING, $response->getStatus());
        $this->assertNotNull($response->getRedirectUrl());
    }
}