<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Text;


class TextLimiter
{
    public function limitText(string $text = null, int $maxLength = -1, string $ellipsis = '...')
    {
        if ($maxLength < 0) {
            return $text;
        }

        $length = strlen($text);

        if ($length < $maxLength) {
            return $text;
        }

        return substr($text, 0, $maxLength) . $ellipsis;
    }
}