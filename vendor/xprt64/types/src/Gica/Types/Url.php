<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Types;


use Psr\Http\Message\UriInterface;

class Url
{
    /** @var UriInterface */
    private $uri;

    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    public function withQueryParam($paramName, $newParamValue)
    {
        $query = $this->uri->getQuery();

        parse_str($query, $parts);

        foreach ($parts as $k => $v) {
            if (0 === stripos($k, '_')) {
                unset($parts[$k]);
            }
        }

        if (null === $newParamValue) {
            unset($parts[$paramName]);
        } else {
            $parts[$paramName] = $newParamValue;
        }


        $newQuery = \http_build_query($parts);

        return new self($this->uri->withQuery($newQuery));
    }

    function __toString()
    {
        return $this->uri->__toString();
    }

    public function hasQueryParam($paramName)
    {
        $query = $this->uri->getQuery();

        parse_str($query, $parts);

        return isset($parts[$paramName]);
    }

    public function getQueryParam($paramName)
    {
        $query = $this->uri->getQuery();

        parse_str($query, $parts);

        return $parts[$paramName];
    }

    public function getQueryParams()
    {
        $query = $this->uri->getQuery();

        parse_str($query, $parts);

        return $parts;
    }
}