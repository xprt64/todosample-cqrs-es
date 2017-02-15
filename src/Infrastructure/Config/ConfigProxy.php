<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Config;


class ConfigProxy
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getConfig()
    {
        return $this->container->get('config');
    }
}