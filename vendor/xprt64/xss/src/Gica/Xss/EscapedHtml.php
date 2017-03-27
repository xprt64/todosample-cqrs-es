<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Xss;


class EscapedHtml
{
    /**
     * @var null
     */
    private $html;

    public function __construct($html = null)
    {
        $this->html = $html;
    }

    public function __toString()
    {
        return htmlentities($this->html, ENT_QUOTES, 'utf-8');
    }
}