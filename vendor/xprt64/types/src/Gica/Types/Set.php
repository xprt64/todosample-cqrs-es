<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types;


use Gica\Types\Exception\InvalidValue;

abstract class Set
{
    /** @var integer */
    private $primitiveValues;
    /**
     * @var bool
     */
    private $nullable;

    protected function __construct(array $primitiveValues = null, $nullable = true)
    {
        if (null === $primitiveValues) {
            $primitiveValues = [];
        }

        $this->validateValue($primitiveValues, $nullable);

        foreach ($primitiveValues as &$value) {
            $value = $this->convertPrimitiveValue($value);
        }

        $this->primitiveValues = array_unique($primitiveValues, \SORT_REGULAR);
        $this->nullable = $nullable;
    }

    public function validateValue($primitiveValues, $nullable)
    {
        if (null === $primitiveValues && $nullable) {
            return;
        }

        $all = $this->getAll();

        foreach ($primitiveValues as $primitiveValue) {
            if (!in_array($primitiveValue, $all)) {
                throw new InvalidValue(
                    sprintf(
                        "%s is not a valid value in set %s", $this->escapeString($primitiveValue), get_class($this)));
            }
        }
    }

    private function escapeString($s)
    {
        return htmlentities($s, ENT_QUOTES, 'utf-8');
    }

    public function format(callable $formatter, callable $reducer)
    {
        $resultMap = [];
        foreach ($this->primitiveValues as $primitiveValue)
            $resultMap[] = $formatter($primitiveValue);

        return $reducer($resultMap);
    }

    public function implode(callable $formatter, $glue)
    {
        return $this->format($formatter, function ($formattedItems) use ($glue) {
            return implode($glue, $formattedItems);
        });
    }

    public function toPrimitive()
    {
        if (!$this->primitiveValues) {
            return [];
        }

        $result = [];

        foreach ($this->primitiveValues as $k => $value) {
            $result[$k] = $this->convertPrimitiveValue($value);
        }
        return $result;
    }

    public static function fromPrimitive($values)
    {
        if (null !== $values) {
            if (!is_array($values)) {
                $values = [$values];
            }
        }

        return new static($values);
    }

    public function isNull()
    {
        return null === $this->primitiveValues;
    }

    public function isEmpty()
    {
        return empty($this->primitiveValues);
    }

    abstract public function getAll();

    public function containsAll(self $other)
    {
        return count(array_intersect($this->primitiveValues, $other->primitiveValues)) === count($other->primitiveValues);
    }

    public function containsAny(?self $other)
    {
        return null !== $other && count(array_intersect($this->primitiveValues, $other->primitiveValues)) > 0;
    }

    public function containsPrimitive($primitiveValue)
    {
        return in_array($primitiveValue, $this->primitiveValues);
    }

    private function convertPrimitiveValue($value)
    {
        if ($this->hasPrimitiveInteger() && null !== $value) {
            $value = (int)$value;
        }

        return $value;
    }

    protected function hasPrimitiveInteger()
    {
        return is_integer($this->getAll()[0]);
    }

    public function merge(?self $other)
    {
        $primitives = $this->primitiveValues;

        if ($other) {
            foreach ($other->primitiveValues as $primitiveValue) {
                if (!in_array($primitiveValue, $primitives)) {
                    $primitives[] = $primitiveValue;
                }
            }
        }

        return new static($primitives);
    }

    public function diff(?self $other)
    {
        $primitives = [];

        if ($other) {
            foreach ($this->primitiveValues as $primitiveValue) {
                if (!in_array($primitiveValue, $other->primitiveValues)) {
                    $primitives[] = $primitiveValue;
                }
            }
        }

        return new static($primitives);
    }

    public function equals(?self $operand):bool
    {
        $a = $this->toPrimitive();
        $b = $operand->toPrimitive();

        sort($a);
        sort($b);

        return null !== $operand && $a === $b;
    }

}