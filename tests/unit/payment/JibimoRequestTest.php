<?php

declare(strict_types=1);


namespace unit\payment;


use Mockery;
use PHPUnit\Framework\TestCase;
use puresoft\jibimo\api\Request;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\request\RequestTransactionRequest;
use puresoft\jibimo\payment\JibimoRequest;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoRequestTest extends TestCase
{
    /**
     * Test case close up validations.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * This method will test a normal money request transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanRequestMoney()
    {

        // Mocked CURL request class
        $curlMockery = Mockery::mock(CurlRequest::class);

        // Expected headers
        $headers = [
            'Authorization: Bearer ' . trim($GLOBALS['token']),
            'Accept: application/json',
        ];

        // Expected data
        $data = [
            'mobile_number' => '+989366061280',
            'amount' => 8500,
            'privacy' => 'Personal',
            'tracker_id' => '85',
        ];

        // Mock response
        $mockResponse = new CurlResult(200, '{"id":2520,"tracker_id":"85","amount":8500,
        "payer":"+989366061280","privacy":"Personal","status":"Pending","created_at":{"date":"2019-05-25 22:26:19.000000",
        "timezone_type":3,"timezone":"Asia\/Tehran"},"updated_at":{"date":"2019-05-25 22:26:19.000000","timezone_type":3,
        "timezone":"Asia\/Tehran"},"description":null, "redirect": "https://example.com"}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/request_transaction", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoRequest = new JibimoRequest(new Request($curlMockery));


        $request = new RequestTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");


        $response = $jibimoRequest->request($request);

        $this->assertEquals(JibimoTransactionStatus::PENDING, $response->getStatus());
        $this->assertNotNull($response->getRedirectUrl());
    }
}