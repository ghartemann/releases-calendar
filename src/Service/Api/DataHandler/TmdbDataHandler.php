<?php

namespace App\Service\Api\DataHandler;

class TmdbDataHandler
{
    public function formatData(array $data): array
    {
        $data = $data['results'];

        // TODO: code

        return $data;
    }

    /**
     * This function sorts the crew members by their job. It uses the 'credits' key of the detailed movie data.
     */
    public function sortCrew(array $crew): array
    {
        $sortedCrew = [];

        foreach ($crew as $member) {
            if ($member['job'] === 'Director') {
                $sortedCrew[] = $member;
            }
        }

        foreach ($crew as $member) {
            if ($member['job'] === 'Executive producer') {
                $sortedCrew[] = $member;
            }
        }

        foreach ($crew as $member) {
            if ($member['job'] === 'Producer') {
                $sortedCrew[] = $member;
            }
        }

        foreach ($crew as $member) {
            if ($member['job'] === 'Writer') {
                $sortedCrew[] = $member;
            }
        }

        return $sortedCrew;
    }
}
