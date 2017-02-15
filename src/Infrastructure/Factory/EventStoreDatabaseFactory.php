<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Factory;


use Infrastructure\Config\ConfigProxy;
use Zend\ServiceManager\Factory\FactoryInterface;

class EventStoreDatabaseFactory implements FactoryInterface
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

    /**
     * @inheritdoc
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        return new \Crm\EntityTypeMapper();
    }

    public function selectDatabase(string $databaseName):\MongoDB\Database
    {
        $client = new \MongoDB\Client($this->configProxy->getConfig()['mongoEventStore']['dsn'] . $databaseName, [], [
            'typeMap' => [
                'array'    => 'array',
                'document' => 'array',
                'root'     => 'array',
            ],
        ]);

        return $client->selectDatabase($databaseName);
    }
}