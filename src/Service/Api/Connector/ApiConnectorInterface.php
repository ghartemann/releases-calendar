<?php


namespace App\Service\Api\Connector;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiConnectorInterface
{
    public function query(string $uri, string $method = 'GET', array $data = [], array $metadata = []): ResponseInterface;

    public function getContentAsArray(ResponseInterface $response): array;

    public static function getDataHandler(): string;
}
