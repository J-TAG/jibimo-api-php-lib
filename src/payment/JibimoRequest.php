<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\api\Request;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevel;
use puresoft\jibimo\exceptions\InvalidJibimoResponse;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatus;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\request\RequestTransactionRequest;
use puresoft\jibimo\models\request\RequestTransactionResponse;

class JibimoRequest extends AbstractTransactionProvider
{

    /**
     * Using this method you can perform a Jibimo request money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param RequestTransactionRequest $request The object request transaction which is contains its request data to be
     * send to Jibimo API.
     * @return RequestTransactionResponse An object that will have data about response of this request.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevel
     * @throws InvalidJibimoResponse
     * @throws InvalidJibimoTransactionStatus
     * @throws InvalidMobileNumberException
     */
    public function request(RequestTransactionRequest $request): RequestTransactionResponse
    {
        $this->request = $request;

        $curlResult = Request::request($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
            $request->getAmount(), $request->getPrivacy(), $request->getTrackerId(), $request->getDescription(),
            $request->getReturnUrl());

        $jsonResult = $this->convertRawDataToJson($curlResult);

        // Convert raw response data to relevant object
        $response = new RequestTransactionResponse($curlResult->getResult(), $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payer, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->redirect,
            $jsonResult->description);

        $this->response = $response;

        return $response;
    }

}