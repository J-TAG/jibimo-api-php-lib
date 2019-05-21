<?php


namespace puresoft\jibimo;


use puresoft\jibimo\api\Request;

class JibimoRequest
{
    private $token;
    private $baseUrl;

    /**
     * JibimoRequest constructor.
     * @param string $token
     * @param string $baseUrl
     */
    public function __construct(string $token, string $baseUrl)
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    public function request(string $mobileNumber, int $amount, string $privacy, ?string $description, ?string $trackerId,
                            ?string $returnUrl)
    {
        return Request::request($this->baseUrl, $this->token, $mobileNumber, $amount, $privacy, $description, $trackerId, $returnUrl);
    }
}