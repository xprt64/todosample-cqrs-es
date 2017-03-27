<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Xss;


class CleanJs
{
    public function clean($code)
    {
        if ($code) {
            $code = preg_replace('#<script(.*?)/script([^>]*?)>#ims', '', $code);
            $code = preg_replace('#<script(.*?)>#ims', '', $code);
            $code = $this->cleanMethods($code);
        }

        return $code;
    }

    public	function	cleanMethods($code)
    {
        static $events  =   null;

        if(null === $events)
            $events =   '((' . implode(')|(', explode('|', 'blur|change|copy|cut|focus|keydown|keypress|keyup|paste|reset|select|submit|copy|cut'
                    . '|paste|keydown|keypress|keyup|click|contextmenu|dblclick|mousedown|mousemove|mouseout|mouseover|mouseup|right|click|scrolling')) . '))';

        if($code)
            return	preg_replace('#(\s)on' . $events . '(\s*)=#ims', ' data-replaced-evil-attribute=', $code);
        else
            return	$code;
    }
}