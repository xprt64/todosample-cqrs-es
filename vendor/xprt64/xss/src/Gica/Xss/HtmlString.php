<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace Gica\Xss;


class HtmlString
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
    public function __construct($format, ...$parameters)
    {
        $this->parameters = $parameters;
        $this->format = $format;
    }

    public function __toString()
    {
        $safeParameters = array_map(function($unsafe){
            return htmlentities($unsafe, ENT_QUOTES, 'utf-8');
        }, $this->parameters);

        array_unshift($safeParameters, $this->format);

        return (string)call_user_func_array('sprintf', $safeParameters);
    }
}