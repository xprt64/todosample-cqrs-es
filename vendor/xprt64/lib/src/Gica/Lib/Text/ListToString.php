<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Text;


class ListToString
{

    private $list;
    /**
     * @var callable
     */
    private $callback;
    /**
     * @var string
     */
    private $glue;

    public function __construct($list, $glue = ',', callable $callback = null)
    {
        $this->list = $list;
        $this->callback = $callback;
        $this->glue = $glue;
    }

    function __toString()
    {
        $s = [];

        foreach ($this->list as $k => $item) {
            if ($this->callback) {
                $item = call_user_func($this->callback, $item, $k);
            }

            $s[] = (string)$item;
        }

        return implode($this->glue, $s);
    }
}