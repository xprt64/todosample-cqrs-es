<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Serialize\ObjectSerializer;


use Gica\Serialize\ObjectSerializer\Exception\ValueNotSerializable;

class CompositeSerializer implements Serializer
{
    /**
     * @var Serializer[]
     */
    private $serializers;

    /**
     * @param Serializer[] $serializers
     */
    public function __construct(
        array $serializers
    )
    {
        $this->serializers = $serializers;
    }

    public function serialize($value)
    {
        foreach ($this->serializers as $serializer) {
            try {
                return $serializer->serialize($value);
            } catch (ValueNotSerializable $exception) {
                continue;
            }
        }

        throw new ValueNotSerializable("None of the serializers worked");
    }
}