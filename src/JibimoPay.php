<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\internals\AbstractTransactionProvider;
use puresoft\jibimo\models\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\ExtendedPayTransactionResponse;
use puresoft\jibimo\models\PayTransactionRequest;
use puresoft\jibimo\models\PayTransactionResponse;

class JibimoPay extends AbstractTransactionProvider
{

    /**
     * Using this method you can perform a Jibimo pay money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param PayTransactionRequest $request The object pay transaction which is contains its request data to be
     * send to Jibimo API.
     * @return PayTransactionResponse An object that will have data about response of this request.
     * @throws CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevel
     * @throws exceptions\InvalidJibimoResponse
     * @throws exceptions\InvalidMobileNumberException
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function pay(PayTransactionRequest $request)
    {
        $this->request = $request;

        $curlResult = Pay::pay($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
            $request->getAmount(), $request->getPrivacy(), $request->getTrackerId(), $request->getDescription());

        $jsonResult = $this->convertRawDataToJson($curlResult);

        // Convert raw response data to relevant object
        $response = new PayTransactionResponse($curlResult->getResult(), $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payee, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);

        $this->response = $response;

        return $response;
    }

    /**
     * Using this method you can perform a Jibimo extended pay money transaction to a mobile number and an IBAN which
     * its owner may or may not be in Jibimo.
     * @param ExtendedPayTransactionRequest $request
     * @return ExtendedPayTransactionResponse CURL execution result.
     * @throws CurlResultFailedException
     * @throws exceptions\InvalidJibimoPrivacyLevel
     * @throws exceptions\InvalidJibimoResponse
     * @throws exceptions\InvalidMobileNumberException
     * @throws exceptions\InvalidJibimoTransactionStatus
     */
    public function extendedPay(ExtendedPayTransactionRequest $request)
    {
        $this->request = $request;

        $curlResult = Pay::extendedPay($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
            $request->getAmount(), $request->getPrivacy(), $request->getIban(), $request->getTrackerId(),
            $request->getDescription(), $request->getName(), $request->getFamily());

        $jsonResult = $this->convertRawDataToJson($curlResult);

        // Convert raw response data to relevant object
        $response = new ExtendedPayTransactionResponse($curlResult->getResult(), $jsonResult->id, $jsonResult->tracker_id,
            $jsonResult->amount, $jsonResult->payee, $jsonResult->privacy, $jsonResult->status,
            $jsonResult->created_at->date, $jsonResult->updated_at->date, $jsonResult->description);

        $this->response = $response;

        return $response;
    }

}