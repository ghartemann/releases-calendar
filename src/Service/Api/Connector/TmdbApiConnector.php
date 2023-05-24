<?php

namespace App\Service\Api\Connector;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmdbApiConnector extends AbstractApiConnector
{
    public function __construct(private readonly HttpClientInterface $client,)
    {
    }


    public function fetchUpcoming(array $params, string $url): array
    {
        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['TMDB_HEADER'],
                'accept' => 'application/json',
            ],
            'query' => $params
        ]);

        return $response->toArray();
    }
}
