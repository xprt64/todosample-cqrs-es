<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class StringRebaser
{
    private $fromBase = '';
    private $toBase = '';

    /**
     * @param string $toBase
     * @param string $fromBase
     */
    public function __construct($fromBase, $toBase)
    {
        $this->fromBase = $fromBase;
        $this->toBase = $toBase;
    }

    /**
     * @param $stringToBeRebased
     * @return string
     * @throws \InvalidArgumentException
     */
    public function rebase($stringToBeRebased)
    {
        if (false === stripos($stringToBeRebased, $this->fromBase)) {
            throw new \InvalidArgumentException(sprintf("Input %s must start with %s", $stringToBeRebased, $this->fromBase));
        }

        $append = substr($stringToBeRebased, strlen($this->fromBase));

        return $this->toBase . $append;
    }
}