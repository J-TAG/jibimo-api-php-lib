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
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

}