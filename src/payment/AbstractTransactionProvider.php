<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\internals\CurlResult;
use puresoft\jibimo\models\AbstractTransactionRequest;
use puresoft\jibimo\models\AbstractTransactionResponse;

abstract class AbstractTransactionProvider
{
    /** @var $request AbstractTransactionRequest */
    protected $request;

    /** @var $response AbstractTransactionResponse */
    protected $response;

    /**
     * @param CurlResult $curlResult
     * @return mixed
     * @throws InvalidJibimoResponseException
     */
    protected function convertRawDataToJson(CurlResult $curlResult) {
        $rawResult = $curlResult->getResult();
        $httpStatusCode = $curlResult->getHttpStatusCode();

        $jsonResult = json_decode($rawResult);

        if(empty($jsonResult->id) or $httpStatusCode !== 200) {

            // Response is not a transaction

            throw new InvalidJibimoResponseException("Unexpected result received from Jibimo API: HTTP status: $httpStatusCode.
            Raw result: `$rawResult`");

        }

        return $jsonResult;
    }

    /**
     * @return AbstractTransactionRequest|null
     */
    public function getRequest(): ?AbstractTransactionRequest
    {
        return $this->request;
    }

    /**
     * @return AbstractTransactionResponse|null
     */
    public function getResponse(): ?AbstractTransactionResponse
    {
        return $this->response;
    }
}