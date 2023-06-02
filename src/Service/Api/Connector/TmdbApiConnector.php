<?php

namespace App\Service\Api\Connector;

use App\Service\Api\DataHandler\TmdbDataHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmdbApiConnector extends AbstractApiConnector
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly TmdbDataHandler $tmdbDataHandler,
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
            'query' => $params
        ]);

        return $this->tmdbDataHandler->formatData($response->toArray());
    }

    public function fetchDetails(array $params, int $id, string $urlComplement): array
    {
        //TODO: ça

        $response = $this->client->request('GET', $this->url . $urlComplement, [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['TMDB_HEADER'],
                'accept' => 'application/json',
            ],
            'query' => $params['data']
        ]);

        return $this->tmdbDataHandler->formatData($response->toArray());
    }
}
