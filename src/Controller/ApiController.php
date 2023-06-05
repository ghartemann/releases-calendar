<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\UpcomingManager;
use App\Service\Api\Connector\TmdbApiConnector;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/get-upcoming-{type}', name: 'get_upcoming')]
    public function getUpcoming(
        string $type,
        UpcomingManager $upcomingManager,
        ApiService $apiService,
        TmdbApiConnector $tmdbApiConnector,
        Request $request
    ): JsonResponse
    {
        $params = $request->toArray()['params'];
        $period = $request->toArray()['period'];
        $nbItems = $request->toArray()['nbItems'];

        $dbDataDirty = $apiService->isDbDataDirtyOrMissing($type, $period, $nbItems);

        if ($dbDataDirty === true) {
            $data = match ($type) {
                'movies', 'tv' => $tmdbApiConnector->fetchUpcoming($params, $type),
                // TODO: games
                default => throw new AppException('Invalid type'),
            };

            $upcomingManager->saveUpcoming($type, $period, $data, $nbItems);
        }

        $upcoming = $upcomingManager->getLastEntry($type, $period);

        //TODO: c pa ouf
        if ($upcoming === null) {
            return new JsonResponse();
        }

        return $this->json($upcoming);
    }

    #[Route('/get-details/{type}/{id}', name: 'get_details')]
    public function getDetails(
        string $type,
        int $id,
        TmdbApiConnector $tmdbApiConnector,
        Request $request
    ): JsonResponse
    {
        $params = $request->toArray()['params'];

        $data = match ($type) {
            'movies', 'tv' => $tmdbApiConnector->fetchDetails($params, $id, $type),
            // TODO: games
            default => throw new AppException('Invalid type'),
        };

        return $this->json($data);
    }
}
