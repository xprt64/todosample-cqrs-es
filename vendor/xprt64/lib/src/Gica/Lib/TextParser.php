<?php
/**
 * @copyright Constantin Galbenu gica.galbenu@gmail.com
 * All rights reserved.
 */

namespace Gica\Lib;


class TextParser
{
    public function extractAndReplaceTextBetween(&$text, $startToken, $endToken, $replacement, $includeStart, $includeEnd)
    {
        list($x, $y)    =   $this->findTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd);

        if(-1 === $x)
            return '';

        $extracted	=	substr($text, $x, $y - $x);

        $text	=	substr_replace($text, $replacement, $x, $y - $x);

        return	$extracted;
    }

    public function replaceTextBetween(&$text, $startToken, $endToken, $replacement, $includeStart, $includeEnd)
    {
        list($x, $y)    =   $this->findTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd);

        if(-1 === $x)
            return;

        $text	=	substr_replace($text, $replacement, $x, $y - $x);
    }

    public function replaceAllTextsBetween(&$text, $startToken, $endToken, $replacement, $includeStart, $includeEnd)
    {
        do
        {
            list($x, $y)    =   $this->findTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd);

            if(-1 === $x)
                return;

            $text	=	substr_replace($text, $replacement, $x, $y - $x);
        }
        while(true);
    }

    public function extractTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd)
    {
        list($x, $y)    =   $this->findTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd);

        if(-1 === $x)
            return '';

        $extracted	=	substr($text, $x, $y - $x);

        return	$extracted;
    }


    public function findTextBetween($text, $startToken, $endToken, $includeStart, $includeEnd)
    {
        $s	=	stripos($text, $startToken, 0);
        if(false === $s)
        {
            return [-1, -1];
        }

        $e	=	stripos($text, $endToken, $s + strlen($startToken));
        if(false === $e)
        {
            return [-1, -1];
        }

        if($includeStart)
            $x	=	$s;
        else
            $x	=	$s + strlen($startToken);

        if(!$includeEnd)
            $y	=	$e;
        else
            $y	=	$e + strlen($endToken);

        return [$x, $y];
    }

}