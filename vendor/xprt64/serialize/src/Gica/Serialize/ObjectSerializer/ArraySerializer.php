<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectSerializer;


class ArraySerializer
{
    /**
     * @var ObjectSerializer
     */
    private $objectSerializer;

    public function __construct(
        ObjectSerializer $objectSerializer
    )
    {
        $this->objectSerializer = $objectSerializer;
    }

    public function convertArray($array)
    {
        $result = [];

        $array = $array ?: [];

        foreach ($array as $value) {
            $result[] = $this->objectSerializer->convert($value);
        }
        return $result;
    }
}