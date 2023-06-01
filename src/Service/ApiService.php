<?php

namespace App\Service;

use App\Manager\UpcomingManager;
use App\Repository\UpcomingRepository;

class ApiService
{
    public function __construct(
        private readonly UpcomingManager $upcomingManager,
    )
    {

    }


    public function isDbDataDirtyOrMissing(string $type, int $period): bool
    {
        /** @var UpcomingRepository $upcomingRepository */
        $upcomingRepository = $this->upcomingManager->getClassRepository();

        $upcoming = $upcomingRepository->findBy(['type' => $type, 'period' => $period], ['createdAt' => 'DESC'], 1);

        return empty($upcoming) || $upcoming[0]->getCreatedAt()->diff(new \DateTimeImmutable())->days > 1;
    }

    public function sortDataByDate(array $data): array
    {
        usort($data, function ($a, $b) {
            return $a['release_date'] <=> $b['release_date'];
        });

        return $data;
    }
}
