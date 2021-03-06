<?php

declare(strict_types=1);

namespace unit\payment;


use Mockery;
use PHPUnit\Framework\TestCase;
use puresoft\jibimo\api\Pay;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\pay\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\pay\PayTransactionRequest;
use puresoft\jibimo\payment\JibimoPay;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoPayTest extends TestCase
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
     * This method will test a normal pay transaction.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanDoNormalPay(): void
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
        "payee":"+989366061280","privacy":"Personal","status":"Accepted","created_at":"2019-06-15T16:50:03.000000Z",
        "updated_at":"2019-06-15T16:50:03.000000Z","description":null}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/pay", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoPay = new JibimoPay(new Pay($curlMockery));

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
        // Mocked CURL request class
        $curlMockery = Mockery::mock(CurlRequest::class);

        // Expected headers
        $headers = [
            'Authorization: Bearer ' . trim($GLOBALS['token']),
            'Accept: application/json',
        ];

        // Expected data
        $data = [
            'mobile_number' => "+989366061280",
            'amount' => 8500,
            'privacy' => 'Personal',
            'iban' => '140570028870010133089001',
            'tracker_id' => '85',
        ];

        // Mock response
        $mockResponse = new CurlResult(200, '{"id":2520,"tracker_id":"85","amount":8500,
        "payee":"+989366061280","privacy":"Personal","status":"Accepted","created_at":"2019-06-15T16:50:03.000000Z",
        "updated_at":"2019-06-15T16:50:03.000000Z","description":null}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/extended-pay", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoPay = new JibimoPay(new Pay($curlMockery));

        $request = new ExtendedPayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");


        $response = $jibimoPay->extendedPay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());
    }
}