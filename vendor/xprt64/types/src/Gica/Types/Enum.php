<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types;


use Gica\Types\Exception\InvalidValue;

abstract class Enum
{
    /** @var integer */
    protected $primitiveValue;
    /**
     * @var bool
     */
    private $nullable;

    protected function __construct($value = null, $nullable = true)
    {
        $value = $this->replaceNull($value);

        $this->validateValue($value, $nullable);

        $this->primitiveValue = $value;
        $this->nullable = $nullable;
    }

    function __toString()
    {
        return (string)$this->primitiveValue;
    }

    public function toPrimitive()
    {
        return $this->convertPrimitiveValue($this->primitiveValue);
    }

    public function equals(?self $operand)
    {
        return null !== $operand && $this->toPrimitive() === $operand->toPrimitive();
    }

    public function equalsPrimitive($operand)
    {
        return $this->primitiveValue == $operand;
    }

    public static function fromPrimitive($value)
    {
        return new static($value);
    }

    public function format(callable $formatter)
    {
        return $formatter($this->primitiveValue);
    }

    public function formatByMap(array $map)
    {
        return $map[$this->primitiveValue];
    }

    private function replaceNull($primitiveValue)
    {
        return '__null__' === $primitiveValue ? null : $primitiveValue;
    }

    public function validateValue($primitiveValue, $nullable)
    {
        if (null === $primitiveValue && $nullable) {
            return;
        }

        $all = $this->getAll();

        if (!in_array($primitiveValue, $all, false)) {
            throw new InvalidValue(sprintf("%s (%s) is not a valid value in enum %s (%s)", $this->escapeString($primitiveValue), gettype($primitiveValue), get_class($this), print_r($all, 1)));
        }
    }


    private function escapeString($s)
    {
        return htmlentities($s, ENT_QUOTES, 'utf-8');
    }

    public function isNull()
    {
        return null === $this->primitiveValue;
    }

    abstract public function getAll();

    public static function fromString(string $value)
    {
        $cls = new    \ReflectionClass(get_called_class());

        $constants = $cls->getConstants();

        return $constants[$value];
    }

    private function convertPrimitiveValue($value)
    {
        if ($this->hasPrimitiveInteger() && null !== $value && '' !== $value) {
            $value = (int)$value;
        }

        return $value;
    }

    protected function hasPrimitiveInteger()
    {
        return is_integer($this->getAll()[0]);
    }

    public static function null()
    {
        return new static(null);
    }
}