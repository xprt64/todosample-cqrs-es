<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Implementations;


use Infrastructure\Config\ConfigProxy;
use MongoDB\Client;
use MongoDB\Collection;

class ReadModelsDatabase implements \Domain\Read\Dependency\Database\ReadModelsDatabase
{

    /**
     * @var ConfigProxy
     */
    private $configProxy;

    private $database;

    public function __construct(
        ConfigProxy $configProxy
    )
    {
        $this->configProxy = $configProxy;
    }


    private function selectDatabase()
    {
        $dbConfig = $this->configProxy->getConfig()['mongoReadModels'];

        $client = new Client($dbConfig['dsn'] . $dbConfig['database'], [], [
            'typeMap' => [
                'array'    => 'array',
                'document' => 'array',
                'root'     => 'array',
            ],
        ]);

        return $client->selectDatabase($dbConfig['database']);

    }

    public function selectCollection($collectionName): Collection
    {
        if (!$this->database) {
            $this->database = $this->selectDatabase();
        }

        return $this->database->selectCollection($collectionName);
    }
}