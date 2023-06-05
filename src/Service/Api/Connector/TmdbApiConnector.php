<?php

namespace App\Service\Api\Connector;

use App\Service\Api\DataHandler\TmdbDataHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TmdbApiConnector extends AbstractApiConnector
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly TmdbDataHandler     $tmdbDataHandler,
        private readonly string              $url = "https://api.themoviedb.org/3/"
    )
    {
    }


    public function fetchUpcoming(array $params, string $type): array
    {
        $response = $this->client->request(
            'GET',
            $this->url . 'discover/' . ($type === 'movies' ? substr($type, 0, -1) : $type),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['TMDB_HEADER'],
                    'accept' => 'application/json',
                ],
                'query' => $params,
            ]
        );

        return $this->tmdbDataHandler->formatData($response->toArray()['results']);
    }

    public function fetchDetails(array $params, int $id, string $type): array
    {
        $response = $this->client->request(
            'GET',
            $this->url . ($type === 'movies' ? substr($type, 0, -1) : $type) . '/' . $id,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['TMDB_HEADER'],
                    'accept' => 'application/json',
                ],
                'query' => $params,
            ]
        );

        return $this->tmdbDataHandler->formatIndividualResult($response->toArray());
    }
}
