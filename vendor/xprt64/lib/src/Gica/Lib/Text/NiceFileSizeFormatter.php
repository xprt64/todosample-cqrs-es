<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Text;


class NiceFileSizeFormatter
{
    public function format($size)
    {
        if ($size < 1024)
            return $size . ' B';

        $size /= 1024;
        if ($size < 1024)
            return round($size, 2) . ' KB';

        $size /= 1024;
        if ($size < 1024)
            return round($size, 2) . ' MB';

        $size /= 1024;
        if ($size < 1024)
            return round($size, 2) . ' GB';

        $size /= 1024;
        if ($size < 1024)
            return round($size, 2) . ' TB';

        $size /= 1024;
        if ($size < 1024)
            return round($size, 2) . ' PB';


        return number_format($size, '', '', ',') . ' PB';
    }
}