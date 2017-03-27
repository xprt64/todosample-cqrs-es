<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib;


class ObjectToArrayConverter
{
    public function convert($object)
    {
        $result = $this->extractObjectProperties($object);

        return $result;
    }

    private function extractObjectProperties($obj)
    {
        if (!is_object($obj)) {
            return $obj;
        }

        $class = new \ReflectionClass($obj);

        $properties = $class->getProperties();

        $result = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($obj);

            if (is_object($value)) {
                if (is_callable([$value, '__toString'])) {
                    $value = (string)$value;
                } else {
                    $value = $this->extractObjectProperties($value);
                }
            } else if (is_array($value)) {
                $value = array_map(function ($item) {
                    return $this->extractObjectProperties($item);
                }, $value);
            }

            $result[$property->getName()] = $value;
        }

        return $result;
    }

}