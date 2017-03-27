<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Cqrs\EventStore\Mongo;


class LastAggregateVersionFetcher
{
    public function fetchLatestVersion(\MongoDB\Collection $collection, string $aggregateClass, $aggregateId):int
    {
        $cursor = $collection->find(
            [
                'aggregateId' => (string)$aggregateId,
                'aggregateClass' => $aggregateClass,
            ],
            [
                'sort'  => [
                    'version' => -1,
                ],
                'limit' => 1,
            ]
        );

        $documents = $cursor->toArray();
        if ($documents) {
            $last = array_pop($documents);
            $version = (int)$last['version'];
        } else {
            $version = 0;
        }

        return $version;
    }
}