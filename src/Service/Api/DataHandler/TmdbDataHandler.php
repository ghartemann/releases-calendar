<?php

namespace App\Service\Api\DataHandler;

class TmdbDataHandler
{
    public function formatData(array $data): array
    {
        // TODO: code
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $this->formatIndividualResult($data[$i]);
        }

        return $data;
    }

    public function formatIndividualResult(array $data): array
    {
        if (array_key_exists('credits', $data)) {
            $data['credits']['crew'] = $this->sortCrew($data['credits']['crew']);
        }

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
