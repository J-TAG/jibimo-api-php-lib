<?php


namespace puresoft\jibimo\api;


use puresoft\jibimo\internals\CurlHelper;

class Pay
{
    /**
     * This function will be used to pay to a user which may or may not be registered in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $mobileNumber string Mobile number of a person whom you want to pay to.
     * @param $amount int Amount of payment in Toomaans.
     * @param $privacy string Privacy scope which can be one of `Public`, `Friend` or `Personal`.
     * @param $description string Transaction description which will be appear in feed.
     * @param $trackerId string A UUID which will be used as factor id.
     * @return bool|string CURL execution result.
     */
    public static function pay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy, ?string $description, ?string $trackerId)
    {

        $headers = CurlHelper::jsonBearerHeader($token);

        $data = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount, // ***NOTE*** This amount is in Toomaans
            'privacy' => $privacy,
        ];

        // Optional fields

        if (isset($description)) {
            $data['description'] = $description;
        }

        if (isset($trackerId)) {
            $data['tracker_id'] = $trackerId;
        }

        return CurlHelper::post("$baseUrl/business/pay", $data, $headers);
    }

    /**
     * This function will be used to validate a pay transaction in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $transactionId int The ID of a pay transaction that you requested before.
     * @return bool|string CURL execution result.
     */
    public static function validatePay(string $baseUrl, string $token, int $transactionId)
    {

        $headers = CurlHelper::jsonBearerHeader($token);

        return CurlHelper::get("$baseUrl/business/pay/$transactionId", $headers);
    }


    /**
     * This function will be used to directly transfer money from Jibimo to a bank account.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $mobileNumber string Mobile number of a person whom you want to pay to.
     * @param $amount int Amount of payment in Toomaans.
     * @param $privacy string Privacy scope which can be one of `Public`, `Friend` or `Personal`.
     * @param $iban string The IBAN (Sheba) number of that bank account which you want to transfer money to.
     * @param $description string Transaction description which will be appear in feed.
     * @param $trackerId string A UUID which will be used as factor id. This field is optional but it is highly
     * recommended to fill it with a unique value.
     * @param $name string The first name of the person whom you want to pay to.
     * @param $family string The last name of the person whom you want to pay to.
     * @return bool|string CURL execution result.
     */
    public static function extendedPay(string $baseUrl, string $token, string $mobileNumber, int $amount, string $privacy,  string $iban,
                                       ?string $description, ?string $trackerId, ?string $name, ?string $family)
    {

        $headers = CurlHelper::jsonBearerHeader($token);

        $data = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount, // ***NOTE*** This amount is in Toomaans
            'privacy' => $privacy,
            'iban' => $iban, // Please keep in mind that we should not include `IR` in IBAN
        ];

        // Optional fields

        if (isset($description)) {
            $data['description'] = $description;
        }

        if (isset($trackerId)) {
            $data['tracker_id'] = $trackerId;
        }

        if (isset($name)) {
            $data['name'] = $name;
        }

        if (isset($family)) {
            $data['family'] = $family;
        }

        return CurlHelper::post("$baseUrl/business/extended-pay", $data, $headers);
    }

    /**
     * This function will be used to validate an extended pay transaction in Jibimo.
     * @param string $baseUrl URL of Jibimo API.
     * @param string $token Jibimo API token.
     * @param $transactionId int The ID of an extended pay transaction that you made before.
     * @return bool|string CURL execution result.
     */
    public static function validateExtendedPay(string $baseUrl, string $token, int $transactionId)
    {

        $headers = CurlHelper::jsonBearerHeader($token);

        return CurlHelper::get("$baseUrl/business/extended-pay/$transactionId", $headers);
    }

}