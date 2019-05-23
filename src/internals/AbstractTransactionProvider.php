<?php


namespace puresoft\jibimo\internals;


use puresoft\jibimo\exceptions\InvalidJibimoResponse;
use puresoft\jibimo\models\AbstractTransactionRequest;
use puresoft\jibimo\models\AbstractTransactionResponse;

abstract class AbstractTransactionProvider
{
    /** @var $request AbstractTransactionRequest */
    protected $request;

    /** @var $response AbstractTransactionResponse */
    protected $response;

    protected function convertRawDataToJson(CurlResult $curlResult) {
        $rawResult = $curlResult->getResult();
        $httpStatusCode = $curlResult->getHttpStatusCode();

        $jsonResult = json_decode($rawResult);

        if(empty($jsonResult->id) or $httpStatusCode !== 200) {

            // Response is not a transaction

            throw new InvalidJibimoResponse("Unexpected result received from Jibimo API: HTTP status: $httpStatusCode.
            Raw result: `$rawResult`");

        }

        return $jsonResult;
    }

    /**
     * @return AbstractTransactionRequest
     */
    public function getRequest(): AbstractTransactionRequest
    {
        return $this->request;
    }

    /**
     * @return AbstractTransactionResponse
     */
    public function getResponse(): AbstractTransactionResponse
    {
        return $this->response;
    }
}