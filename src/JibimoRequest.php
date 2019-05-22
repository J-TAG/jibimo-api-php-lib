<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Request;
use puresoft\jibimo\models\RequestTransactionRequest;
use puresoft\jibimo\models\RequestTransactionResponse;

class JibimoRequest
{
    /** @var $request RequestTransactionRequest */
    private $request;

    /** @var $response RequestTransactionResponse */
    private $response;

    /**
     * Using this method you can perform a Jibimo request money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param RequestTransactionRequest $request The object request transaction which is contains its request data to be
     * send to Jibimo API.
     * @return RequestTransactionResponse An object that will have data about response of this request.
     * @throws exceptions\CurlResultFailedException
     */
    public function request(RequestTransactionRequest $request)
    {
        $this->request = $request;

        $curlResponse = Request::request($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
            $request->getAmount(), $request->getPrivacy(), $request->getTrackerId(), $request->getDescription(),
            $request->getReturnUrl());

        $rawResult = $curlResponse->getResult();

        // TODO Handle API error messages
        $jsonResult = json_decode($rawResult);

        // Convert raw response data to relevant object
        $response = new RequestTransactionResponse($rawResult, $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payer, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->redirect,
            $jsonResult->description);

        $this->response = $response;

        return $response;
    }

}