<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types;


use Gica\Types\Guid\Exception\InvalidGuid;

class Guid
{
    /** @var string */
    private $string = '';

    public function __construct($string = null)
    {
        if ($string instanceof self) {
            $this->string = $string->string;
        } else if (func_num_args() === 0) {
            $this->string = $this->binaryToString(self::newRandomBinaryGuid());
        } else {
            self::validateString((string)$string);
            $this->string = (string)$string;
        }
    }

    public static function validateString($string)
    {
        if ('' === $string) {
            throw new InvalidGuid("Empty string is not a valid GUID");
        }

        if (!preg_match('#^[0-9a-f]{10,}$#ims', $string)) {
            throw new InvalidGuid(sprintf("%s is not a valid GUID", htmlentities($string, ENT_QUOTES, 'utf-8')));
        }
    }

    public function __toString()
    {
        return (string)$this->string;
    }

    private static function stringToBinary($string)
    {
        self::validateString($string);

        return hex2bin($string);
    }

    private static function binaryToString($binary)
    {
        return bin2hex($binary);
    }

    public function getBinary()
    {
        return self::stringToBinary($this->string);
    }

    public function equals(?self $other): bool
    {
        return $other && $this->string == $other->string;
    }

    private static function newRandomBinaryGuid()
    {
        return random_bytes(self::getByteLength());
    }

    /**
     * @param $binary
     * @return static
     */
    public static function fromBinary($binary)
    {
        return new static(self::binaryToString($binary));
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static();
    }

    /**
     * @param $string
     * @return static
     */
    public static function fromString(string $string)
    {
        self::validateString($string);

        return new static($string);
    }

    /**
     * @param Guid $src
     * @return static
     */
    public static function fromGuid(self $src)
    {
        return new static($src->string);
    }

    public static function getByteLength(): int
    {
        return 12;
    }

    /**
     * @param $string
     * @return static
     */
    public static function fromFixedString($string)
    {
        return static::fromString(substr(md5(strtolower($string)), 0, Guid::getByteLength() * 2));
    }

}