<?php

namespace App\Service\Api\Connector;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmdbApiConnector extends AbstractApiConnector
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $url = "https://api.themoviedb.org"
    )
    {
    }


    public function fetchUpcoming(array $params, string $urlComplement): array
    {
        $response = $this->client->request('GET', $this->url . $urlComplement, [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['TMDB_HEADER'],
                'accept' => 'application/json',
            ],
            'query' => $params['data']
        ]);

        $data = $response->toArray()['results'];

        return $data;
    }
}
