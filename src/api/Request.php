<?php

namespace puresoft\jibimo\api;

use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\internals\CurlResult;

class Request
{

    /**
     * This function will be used to charge a user which may or may not be registered in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $mobileNumber string Mobile number of a person whom you want to charge.
     * @param $amount int Amount of request in Toomaans.
     * @param $privacy string Privacy scope which can be one of `Public`, `Friend` or `Personal`.
     * @param $trackerId string A UUID which will be used as factor id.
     * @param $description string Transaction description which will be appear in feed.
     * @param $returnUrl string URL to return back after payment.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function request(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                   string $trackerId, ?string $description = null, ?string $returnUrl = null)
    {

        $headers = CurlRequest::jsonBearerHeader($token);

        $data = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount, // ***NOTE*** This amount is in Toomaans
            'privacy' => $privacy,
            'tracker_id' => $trackerId,
        ];

        // Optional fields

        if (isset($description)) {
            $data['description'] = $description;
        }

        if (isset($returnUrl)) {
            $data['return_url'] = $returnUrl;
        }

        return CurlRequest::post("$baseUrl/business/request_transaction", $data, $headers);
    }

    /**
     * This function will be used to validate a money request transaction in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $transactionId int The ID of a money request transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function validateRequest(string $baseUrl, string $token, int $transactionId)
    {

        $headers = CurlRequest::jsonBearerHeader($token);

        return CurlRequest::get("$baseUrl/business/request_transaction/$transactionId", $headers);
    }
}