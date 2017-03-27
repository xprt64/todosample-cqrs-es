<?php
////////////////////////////////////////////////////////////////////////////////
// Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>              /
////////////////////////////////////////////////////////////////////////////////

namespace Gica\Xss;


class NamedHtmlString
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string
     */
    protected $format;

    /**
     * HtmlString constructor.
     * @param array $parameters
     * @param string $format
     */
    public function __construct($format, array $parameters)
    {
        $this->parameters = $parameters;
        $this->format = $format;
    }

    public function __toString()
    {
        $safeParameters = array_map(function ($unsafe) {
            return htmlentities($unsafe, ENT_QUOTES, 'utf-8');
        }, $this->parameters);

        $keys = array_map(function ($key) {
            return '{' . $key . '}';
        }, array_keys($safeParameters));

        return (string)str_replace($keys, $safeParameters, $this->format);
    }
}