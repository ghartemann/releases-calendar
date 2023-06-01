<?php

namespace App\Manager;

use App\Entity\Upcoming;
use App\Repository\UpcomingRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpcomingManager extends AbstractManager
{
    public function saveUpcoming(string $type, int $period, array $data): JsonResponse
    {
        /** @var UpcomingRepository $upcomingRepository */
        $upcomingRepository = $this->getClassRepository();

        $upcoming = new Upcoming();
        $upcoming
            ->setType($type)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent($data)
            ->setPeriod($period);

        $upcomingRepository->save($upcoming, true);

        return new JsonResponse();
    }

    public function getLastEntry(string $type, int $period): ?Upcoming
    {
        /** @var UpcomingRepository $upcomingRepository */
        $upcomingRepository = $this->getClassRepository();

        $upcoming = $upcomingRepository->findBy(
            ['type' => $type, 'period' => $period],
            ['createdAt' => 'DESC'],
            1
        );

        if (empty($upcoming)) {
            return null;
        }

         return $upcoming[0];
    }
}
