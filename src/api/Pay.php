<?php


namespace puresoft\jibimo\api;


use puresoft\jibimo\exceptions\CurlResultFailedException;
use puresoft\jibimo\internals\CurlRequest;
use puresoft\jibimo\internals\CurlResult;

class Pay
{

    /**
     * This function will be used to pay to a user which may or may not be registered in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $mobileNumber string Mobile number of a person whom you want to pay to.
     * @param $amount int Amount of payment in Toomaans.
     * @param $privacy string Privacy scope which can be one of `Public`, `Friend` or `Personal`.
     * @param $trackerId string A UUID which will be used as factor id.
     * @param $description string|null Transaction description which will be appear in feed.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function pay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                               string $trackerId, ?string $description = null)
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

        return CurlRequest::post("$baseUrl/business/pay", $data, $headers);
    }

    /**
     * This function will be used to validate a pay transaction in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $transactionId int The ID of a pay transaction that you requested before.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function validatePay(string $baseUrl, string $token, int $transactionId)
    {

        $headers = CurlRequest::jsonBearerHeader($token);

        return CurlRequest::get("$baseUrl/business/pay/$transactionId", $headers);
    }

    /**
     * This function will be used to directly transfer money from Jibimo to a bank account using IBAN (Sheba) number.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $mobileNumber string Mobile number of a person whom you want to pay to.
     * @param $amount int Amount of payment in Toomaans.
     * @param $privacy string Privacy scope which can be one of `Public`, `Friend` or `Personal`.
     * @param $iban string The IBAN (Sheba) number of that bank account which you want to transfer money to.
     * @param $trackerId string A UUID which will be used as factor id.
     * @param $description string|null Transaction description which will be appear in feed.
     * @param $name string|null The first name of the person whom you want to pay to.
     * @param $family string|null The last name of the person whom you want to pay to.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function extendedPay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,
                                       string $iban, string $trackerId, ?string $description = null,
                                       ?string $name = null, ?string $family = null)
    {

        $headers = CurlRequest::jsonBearerHeader($token);

        $data = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount, // ***NOTE*** This amount is in Toomaans
            'privacy' => $privacy,
            'iban' => $iban, // Please keep in mind that we should not include `IR` in IBAN
            'tracker_id' => $trackerId,
        ];

        // Optional fields

        if (isset($description)) {
            $data['description'] = $description;
        }

        if (isset($name)) {
            $data['name'] = $name;
        }

        if (isset($family)) {
            $data['family'] = $family;
        }

        return CurlRequest::post("$baseUrl/business/extended-pay", $data, $headers);
    }

    /**
     * This function will be used to validate an extended pay transaction in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $transactionId int The ID of an extended pay transaction that you made before.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public static function validateExtendedPay(string $baseUrl, string $token, int $transactionId)
    {

        $headers = CurlRequest::jsonBearerHeader($token);

        return CurlRequest::get("$baseUrl/business/extended-pay/$transactionId", $headers);
    }

}