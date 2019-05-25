<?php

declare(strict_types=1);

namespace unit\payment;


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
     * Test case close up validations.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

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
        // Mocked CURL request class
        $curlMockery = Mockery::mock(CurlRequest::class);

        // Expected headers
        $headers = [
            'Authorization: Bearer ' . trim($GLOBALS['token']),
            'Accept: application/json',
        ];

        // Mock response
        $mockResponse = new CurlResult(200, '{"id":2520,"tracker_id":"85","amount":8500,
        "payer":"+989366061280","privacy":"Personal","status":"Accepted","created_at":{"date":"2019-05-25 22:26:19.000000",
        "timezone_type":3,"timezone":"Asia\/Tehran"},"updated_at":{"date":"2019-05-25 22:26:19.000000","timezone_type":3,
        "timezone":"Asia\/Tehran"},"description":null}');

        // Mock implementations
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/request_transaction/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token'], new Pay($curlMockery),
            new Request($curlMockery));

        $validationResult = $jibimoValidator->validateRequestTransaction(2520, 8500,
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
        "payee":"+989366061280","privacy":"Personal","status":"Accepted","created_at":{"date":"2019-05-25 22:26:19.000000",
        "timezone_type":3,"timezone":"Asia\/Tehran"},"updated_at":{"date":"2019-05-25 22:26:19.000000","timezone_type":3,
        "timezone":"Asia\/Tehran"},"description":null}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/pay", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/pay/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoPay = new JibimoPay(new Pay($curlMockery));


        $request = new PayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "85");


        $response = $jibimoPay->pay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Pay transaction

        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token'], new Pay($curlMockery),
            new Request($curlMockery));

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
        "payee":"+989366061280","privacy":"Personal","status":"Accepted","created_at":{"date":"2019-05-25 22:26:19.000000",
        "timezone_type":3,"timezone":"Asia\/Tehran"},"updated_at":{"date":"2019-05-25 22:26:19.000000","timezone_type":3,
        "timezone":"Asia\/Tehran"},"description":null}');

        // Mock implementations
        $curlMockery->allows()->post($GLOBALS['baseUrl'] . "/business/extended-pay", $data, $headers)->andReturns($mockResponse);
        $curlMockery->allows()->get($GLOBALS['baseUrl'] . "/business/extended-pay/2520", $headers)->andReturns($mockResponse);
        $curlMockery->allows()->jsonBearerHeader($GLOBALS['token'])->andReturns($headers);

        // Inject mock object
        $jibimoPay = new JibimoPay(new Pay($curlMockery));


        $request = new ExtendedPayTransactionRequest($GLOBALS['baseUrl'], $GLOBALS['token'], "+989366061280",
            8500, JibimoPrivacyLevel::PERSONAL, "140570028870010133089001", "85");


        $response = $jibimoPay->extendedPay($request);

        $this->assertEquals(JibimoTransactionStatus::ACCEPTED, $response->getStatus());

        // Now verify that Extended Pay transaction

        $jibimoValidator = new JibimoValidator($GLOBALS['baseUrl'], $GLOBALS['token'], new Pay($curlMockery),
            new Request($curlMockery));

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