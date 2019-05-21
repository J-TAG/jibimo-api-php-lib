<?php


namespace puresoft\jibimo\internals;


class CurlHelper
{

    /**
     * This function will be used for sending POST requests to Jibimo API.
     * @param $url string URL to send POST.
     * @param array $data POST Body data.
     * @param array $headers Request headers.
     * @return bool|string CURL execution result.
     */
    public static function post(string $url, array $data, array $headers)
    {
        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        // This snippet will convert array key values to a string that will be ready for CURL POST data
        $concatenatedData = self::concatDataArray($data);
        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $concatenatedData);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($curlHandler);
        curl_close($curlHandler);
        return $res;
    }

    /**
     * This function will be used for sending GET requests to Jibimo API.
     * @param $url string URL to send POST.
     * @param array $headers Request headers.
     * @return bool|string CURL execution result.
     */
    public static function get(string $url, array $headers)
    {
        $curlHandler = curl_init($url);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($curlHandler);
        curl_close($curlHandler);
        return $res;
    }

    /**
     * This function will get a key-value pair and concatenate it for query part of URL.
     * @param array $data POST Body data.
     * @return string Concatenated data as a string.
     */
    public static function concatDataArray(array $data): string
    {
        // This snippet will convert array key values to a string that will be ready for CURL POST data
        $concatenatedData = implode('&', array_map(
            function ($value, $key) {
                return sprintf("%s=%s", $key, urlencode($value));
            },
            $data,
            array_keys($data)
        ));
        return $concatenatedData;
    }

    /**
     * @param string $token Jibimo API token.
     * @return array Generated headers as an array.
     */
    public static function jsonBearerHeader(string $token)
    {
        return [
            'Authorization: Bearer ' . trim($token),
            'Accept: application/json',
        ];
    }
}