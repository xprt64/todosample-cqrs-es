<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Xss;


class ExternalResourceBlocker
{
    public function blockExternalResources($html, &$blockedCnt = null)
    {
        $html = preg_replace_callback('#src=("|\')(?P<url>.*?)\1#ims', function($matches) use (&$blockedCnt){
            //'src=$1about:blank$1'

            if(0 !== stripos($matches[2], '/') && 0 !== stripos($matches[2], 'cid:'))
            {
                $blockedCnt++;
                return 'src=' . $matches[1] . 'about:blank' . $matches[1];
            }
            else
                return $matches[0];

        }, $html, -1, $cnt);

        $html = preg_replace_callback('#src=([^"\'])(?P<url>.*?)(\s|\>|\<)#ims', function($matches) use (&$blockedCnt){
            //'src=$1about:blank$1'
            $url = $matches[1] . $matches[2];
            if(0 !== stripos($url,'/'))
            {
                $blockedCnt++;
                return 'src=' . 'about:blank' . $matches[3];
            }
            else
                return $matches[0];

        }, $html, -1, $cnt);

        return $html;
    }
}