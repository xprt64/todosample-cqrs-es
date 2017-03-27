<?php
/**
 * @copyright  Copyright (c) Constantin Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace Gica\Types\Guid;


use Gica\Types\Guid;

class NullGuid extends Guid
{
    public function __construct()
    {
    }

    public function __toString()
    {
        return '';
    }

    public function getBinary()
    {
        return null;
    }
}