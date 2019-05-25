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
     * This method will convert a CURL result set to JSON object and return it back.
     * @param CurlResult $curlResult The CURL result set to create JSON object from.
     * @return mixed JSON object which was created from CURL result.
     * @throws InvalidJibimoResponseException
     */
    protected function convertRawDataToJson(CurlResult $curlResult) {
        $rawResult = $curlResult->getResult();
        $httpStatusCode = $curlResult->getHttpStatusCode();

        $jsonResult = json_decode($rawResult);

        if(is_null($jsonResult) or $jsonResult === false or empty($jsonResult->id) or $httpStatusCode !== 200) {

            // Response is not a transaction

            throw new InvalidJibimoResponseException("Unexpected result received from Jibimo API: HTTP status: $httpStatusCode.
            Raw result: `$rawResult`");

        }

        return $jsonResult;
    }

    /**
     * Returns the request transaction object. Note that you can type cast this object to subtypes to get your specific
     * result.
     * @return AbstractTransactionRequest|null Request transaction object if it is presented.
     */
    public function getRequest(): ?AbstractTransactionRequest
    {
        return $this->request;
    }

    /**
     * Returns the response transaction object. Note that you can type cast this object to subtypes to get your specific
     * result.
     * @return AbstractTransactionResponse|null Response transaction object if it is presented.
     */
    public function getResponse(): ?AbstractTransactionResponse
    {
        return $this->response;
    }
}