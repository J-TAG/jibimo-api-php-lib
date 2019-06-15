<?php

declare(strict_types=1);

namespace unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use puresoft\jibimo\api\Pay;
use puresoft\jibimo\api\Request;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\Jibimo;
use puresoft\jibimo\models\verification\ExtendedPayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\PayTransactionVerificationResponse;
use puresoft\jibimo\models\verification\RequestTransactionVerificationResponse;
use puresoft\jibimo\payment\values\JibimoPrivacyLevel;
use puresoft\jibimo\payment\values\JibimoTransactionStatus;

class JibimoTest extends TestCase
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
     * This method will test a normal request transaction in Jibimo factory class.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function testCanRequestCharge(): void
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
        "payer":"+989366061280","privacy":"Personal","status":"Pending","created_at":"2019-06-15T16:50:03.000000Z",
        "updated_at":"2019-06-15T16:50:03.000000Z","description":null, "redirect": "https://example.com"}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/request_transaction", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $response = Jibimo::request($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85", null, null,
            new Request($curlMockery));

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
        $response = Jibimo::pay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85", null,
            new Pay($curlMockery));

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
        $response = Jibimo::extendedPay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85",
            null, null, null, new Pay($curlMockery));

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
        // Mocked CURL request class
        $curlMockery = Mockery::mock(CurlRequest::class);

        // Expected headers
        $headers = [
            'Authorization: Bearer ' . trim($GLOBALS['token']),
            'Accept: application/json',
        ];

        // Mock response
        $mockResponse = new CurlResult(200, '{"id":2520,"tracker_id":"85","amount":8500,
        "payer":"+989366061280","privacy":"Personal","status":"Accepted","created_at":"2019-06-15T16:50:03.000000Z",
        "updated_at":"2019-06-15T16:50:03.000000Z","description":null}');

        // Mock implementations
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/request_transaction/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $validationResult = Jibimo::validateRequest($GLOBALS['baseUrl'], $GLOBALS['token'], 2520,
            "+989366061280",8500, "85", new Pay($curlMockery),
            new Request($curlMockery));

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
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/pay/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $response = Jibimo::pay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85", null, new Pay($curlMockery));

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Pay transaction

        $validationResult = Jibimo::validatePay($GLOBALS['baseUrl'], $GLOBALS['token'], $response->getTransactionId(),
            "+989366061280", 8500, "85", new Pay($curlMockery), new Request($curlMockery));

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
            'iban' => '140570028870010133089001',
            'tracker_id' => '85',
        ];

        // Mock response
        $mockResponse = new CurlResult(200, '{"id":2520,"tracker_id":"85","amount":8500,
        "payee":"+989366061280","privacy":"Personal","status":"Accepted","created_at":"2019-06-15T16:50:03.000000Z",
        "updated_at":"2019-06-15T16:50:03.000000Z","description":null}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/extended-pay", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/extended-pay/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $response = Jibimo::extendedPay($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85",
            null, null, null, new Pay($curlMockery));

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Extended Pay transaction

        $validationResult = Jibimo::validateExtendedPay($GLOBALS['baseUrl'], $GLOBALS['token'],
            $response->getTransactionId(), "+989366061280",8500, "85",
            new Pay($curlMockery), new Request($curlMockery));

        /** @var $verificationResponse ExtendedPayTransactionVerificationResponse */
        $verificationResponse = $validationResult->getResponse();

        $this->assertTrue($validationResult->isValid());
        $this->assertTrue($validationResult->isAccepted());
        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $validationResult->getStatus());
        $this->assertEquals("+989366061280", $verificationResponse->getPayee());
    }
}