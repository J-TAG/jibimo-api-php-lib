<?php


namespace puresoft\jibimo\internals;


use puresoft\jibimo\exceptions\CurlResultFailedException;

class CurlRequest implements RequestManagerService
{

    /**
     * This function will be used for sending POST requests to Jibimo API.
     * @param $url string URL to send POST.
     * @param array $data POST Body data.
     * @param array $headers Request headers.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public function post(string $url, array $data, array $headers): CurlResult
    {
        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $url);

        // This snippet will convert array key values to a string that will be ready for CURL POST data
        $concatenatedData = $this->concatDataArray($data);

        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $concatenatedData);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);

        $res = curl_exec($curlHandler);
        $httpStatusCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);

        if ($res === false) {
            // CURL is unable to get result
            throw new CurlResultFailedException("CURL is unable to get result from Jibimo API at `$url`.");
        }

        return new CurlResult($httpStatusCode, $res);
    }

    /**
     * This function will be used for sending GET requests to Jibimo API.
     * @param $url string URL to send POST.
     * @param array $headers Request headers.
     * @return CurlResult CURL execution result.
     * @throws CurlResultFailedException
     */
    public function get(string $url, array $headers): CurlResult
    {
        $curlHandler = curl_init($url);

        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);

        $res = curl_exec($curlHandler);
        $httpStatusCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);

        if ($res === false) {
            // CURL is unable to get result
            throw new CurlResultFailedException("CURL is unable to get result from Jibimo API at `$url`.");
        }

        return new CurlResult($httpStatusCode, $res);
    }

    /**
     * This function will get a key-value pair and concatenate it for query part of URL.
     * @param array $data POST Body data.
     * @return string Concatenated data as a string.
     */
    public function concatDataArray(array $data): string
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
     * This function will get a Bearer token and make an array of HTTP headers required for Jibimo API.
     * @param string $token Jibimo API token.
     * @return array Generated headers as an array.
     */
    public function jsonBearerHeader(string $token): array
    {
        return [
            'Authorization: Bearer ' . trim($token),
            'Accept: application/json',
        ];
    }
}