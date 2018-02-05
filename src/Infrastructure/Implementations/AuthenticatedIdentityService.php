<?php
/**
 * Copyright (c) 2017 Constantin Galbenu <xprt64@gmail.com>
 */

namespace Infrastructure\Implementations;


use Dudulina\Command\CommandDispatcher\AuthenticatedIdentityReaderService;

class AuthenticatedIdentityService implements AuthenticatedIdentityReaderService
{

    /**
     * @inheritdoc
     */
    public function getAuthenticatedIdentityId()
    {
        return null;
    }
}