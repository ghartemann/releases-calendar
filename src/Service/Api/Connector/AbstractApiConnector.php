<?php


namespace App\Service\Api\Connector;


use App\Exception\AppException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class AbstractApiConnector implements ApiConnectorInterface
{
    public function __construct(
        protected readonly string            $url,
        private readonly string              $apiKey,
        private readonly HttpClientInterface $client,
        private readonly string              $headerApi = 'X-API-KEY'
    )
    {
    }


    public function getConnectorName(): string
    {
        $fullname = get_class($this);
        $explodedName = explode("\\", $fullname);

        return $explodedName[count($explodedName) - 1];
    }

    public function query(string $uri, string $method = 'GET', array $data = [], array $metadata = []): ResponseInterface
    {
        if (str_starts_with($uri, 'http')) {
            $url = $uri;
        } else {
            $url = $this->url . $uri;
        }

        try {
            $response = $this->client->request(
                $method,
                $url,
                [
                    'json' => $data,
                    'user_data' => $metadata,
                    'timeout' => 60,
                    'headers' => [
                        $this->headerApi => $this->apiKey,
                    ]
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new AppException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    public function getContentAsArray(ResponseInterface $response): array
    {
        try {
            return $response->toArray();
        } catch (Throwable $e) {
            throw new AppException($e->getMessage(), 500, $e);
        }
    }

    public static function getDataHandler(): string
    {
        throw new AppException('getDataHandler');
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return HttpClientInterface
     */
    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }
}
