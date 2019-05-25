<?php


namespace puresoft\jibimo\internals;


interface RequestManagerService
{
    public function post(string $url, array $data, array $headers): CurlResult;

    public function get(string $url, array $headers): CurlResult;

    public function concatDataArray(array $data): string;

    public function jsonBearerHeader(string $token): array;
}