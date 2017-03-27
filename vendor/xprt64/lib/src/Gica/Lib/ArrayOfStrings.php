<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class ArrayOfStrings
{

    /**
     * @var array
     */
    private $strings;
    /**
     * @var string
     */
    private $separator;

    public function __construct(array $strings, $separator = ',')
    {
        $this->strings = $strings;
        $this->separator = $separator;
    }

    /**
     * @return array
     */
    public function getStrings(): array
    {
        return $this->strings;
    }

    public function __toString()
    {
        return implode($this->separator , $this->strings);
    }
}