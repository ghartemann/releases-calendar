<?php

namespace App\Service;

use App\Repository\UpcomingRepository;

class ApiService
{
    public function isDbDataDirtyOrMissing(UpcomingRepository $upcomingRepository, string $type): bool
    {
        $upcoming = $upcomingRepository->findBy(['type' => $type], ['createdAt' => 'DESC'], 1);

        if (empty($upcoming)) {
            return true;
        }

        return $upcoming[0]->getCreatedAt()->diff(new \DateTimeImmutable())->days > 1;
    }
}
