<?php


namespace puresoft\jibimo\internals;


class CurlResult
{
    private $httpStatusCode;
    private $result;

    /**
     * CurlResult constructor.
     * @param $httpStatusCode int
     * @param $result string
     */
    public function __construct(int $httpStatusCode, string $result)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->result = $result;
    }

    /**
     * This method will return the HTTP status code of current CURL result.
     * @return int The HTTP status code.
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * This method will return back the body text of current CURL result.
     * @return string The body string.
     */
    public function getResult()
    {
        return $this->result;
    }

}