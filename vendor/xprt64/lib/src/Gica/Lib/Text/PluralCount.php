<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Text;


class PluralCount
{
    protected $singular;

    protected $plural;
    private $count;

    public function __construct($count, $singular, $plural)
    {
        $this->singular = $singular;
        $this->plural = $plural;
        $this->count = $count;
    }

    function __toString()
    {
        return $this->count . ' ' . ($this->count == 1 ? $this->singular : $this->plural);
    }
}