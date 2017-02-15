<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Implementations;

use Gica\Dependency\ConstructorAbstractFactory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractFactory implements AbstractFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $namespaces = ['UI\\', 'Domain\\', 'Gica\\', 'Infrastructure\\'];

        foreach ($namespaces as $namespace)
            if (0 === stripos($requestedName, $namespace))
                return true;

        return false;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factory = new ConstructorAbstractFactory($container);

        return $factory->createObject($requestedName);
    }
}