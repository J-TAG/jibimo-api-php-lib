<?php


namespace puresoft\jibimo\payment;


use puresoft\jibimo\api\Pay;
use puresoft\jibimo\api\PayService;
use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\exceptions\InvalidIbanException;
use puresoft\jibimo\exceptions\InvalidJibimoPrivacyLevelException;
use puresoft\jibimo\exceptions\InvalidJibimoResponseException;
use puresoft\jibimo\exceptions\InvalidJibimoTransactionStatusException;
use puresoft\jibimo\exceptions\InvalidMobileNumberException;
use puresoft\jibimo\models\pay\ExtendedPayTransactionRequest;
use puresoft\jibimo\models\pay\ExtendedPayTransactionResponse;
use puresoft\jibimo\models\pay\PayTransactionRequest;
use puresoft\jibimo\models\pay\PayTransactionResponse;

class JibimoPay extends AbstractTransactionProvider
{
    /** @var $payService Pay */
    private $payService;

    /**
     * JibimoPay constructor.
     * @param $payService PayService Pay handler object to use.
     */
    public function __construct(PayService $payService)
    {
        $this->payService = $payService;
    }


    /**
     * Using this method you can perform a Jibimo pay money transaction to a mobile number which may or may not be
     * in Jibimo.
     * @param PayTransactionRequest $request The object pay transaction which is contains its request data to be
     * send to Jibimo API.
     * @return PayTransactionResponse An object that will have data about response of this request.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     */
    public function pay(PayTransactionRequest $request): PayTransactionResponse
    {
        $this->request = $request;

        $curlResult = $this->payService->pay($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
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
     * @param ExtendedPayTransactionRequest $request Request data object.
     * @return ExtendedPayTransactionResponse CURL execution result.
     * @throws CurlResultFailedException
     * @throws InvalidJibimoPrivacyLevelException
     * @throws InvalidJibimoResponseException
     * @throws InvalidJibimoTransactionStatusException
     * @throws InvalidMobileNumberException
     * @throws InvalidIbanException
     */
    public function extendedPay(ExtendedPayTransactionRequest $request): ExtendedPayTransactionResponse
    {
        $this->request = $request;

        $curlResult = $this->payService->extendedPay($request->getBaseUrl(), $request->getToken(), $request->getMobileNumber(),
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