<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis;


class PhpConstantsToJavaScriptExporter
{
    public function export($objOrClass)
    {

        $class = new \ReflectionClass($objOrClass);

        $objectConstants = $class->getConstants();


        $flagsClass = $class->name;

        $constantsDirectives = [];
        $flagsProperties = [];

        foreach ($objectConstants as $constantName => $value) {

            $constantsDirectives[] = 'export const ' . $constantName . ' = ' . json_encode($value) . ";";

            $flagsProperties[] = "export $constantName;";
        }

        $constantsDirectives = implode("\n", $constantsDirectives);

        return <<<TAG
/**
 * @copyright  Galbenu xprt64@gmail.com
 * All rights reserved.
 * generated from PHP class $flagsClass
 */

$constantsDirectives

TAG;
    }
}