<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Factory;


use Infrastructure\Config\ConfigProxy;

class ReadModelsDatabaseFactory implements \Crm\Mongo\Db\ReadModels\ReadModelsDatabaseFactory
{

    /**
     * @var ConfigProxy
     */
    private $configProxy;

    public function __construct(
        ConfigProxy $configProxy
    )
    {
        $this->configProxy = $configProxy;
    }

    public function selectDatabase(string $databaseName):\MongoDB\Database
    {
        $dsn = $this->configProxy->getConfig()['mongoReadModels']['dsn'];

        $client = new \MongoDB\Client($dsn . $databaseName, [], [
            'typeMap' => [
                'array'    => 'array',
                'document' => 'array',
                'root'     => 'array',
            ],
        ]);

        return $client->selectDatabase($databaseName);
    }
}