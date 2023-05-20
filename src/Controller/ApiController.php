<?php

namespace App\Controller;

use App\Entity\Upcoming;
use App\Repository\UpcomingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    #[Route('/save-upcoming-{type}', name: 'create_upcoming')]
    public function saveUpcoming(string $type, Request $request, UpcomingRepository $upcomingRepository): JsonResponse
    {
        $infos = $request->toArray();

        $upcoming = new Upcoming();
        $upcoming
            ->setType($type)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent($infos['items']);

        $upcomingRepository->save($upcoming, true);

        return new JsonResponse();
    }

    #[Route('/get-upcoming-{type}', name: 'get_upcoming')]
    public function getUpcoming(string $type, UpcomingRepository $upcomingRepository): JsonResponse
    {
        $upcoming = $upcomingRepository->findBy(['type' => $type], ['createdAt' => 'DESC'], 1);

        if (empty($upcoming)) {
            return new JsonResponse();
        }

        return $this->json($upcoming[0], 200, [], ['groups' => ['movies']]);
    }
}
