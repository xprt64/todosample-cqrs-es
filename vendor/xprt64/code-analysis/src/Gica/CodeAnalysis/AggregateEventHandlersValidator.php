<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator;
use Gica\CodeAnalysis\Traits\FilesInDirectoryExtracter;

class AggregateEventHandlersValidator
{
    use FilesInDirectoryExtracter;

    /** @var ListenerClassValidator */
    private $classValidator;
    /**
     * @var PhpClassInFileInspector
     */
    private $phpClassInFileInspector;

    public function __construct(
        ListenerClassValidator $classValidator,
        PhpClassInFileInspector $phpClassInFileInspector = null
    )
    {
        $this->classValidator = $classValidator;
        $this->phpClassInFileInspector = $phpClassInFileInspector ?? new PhpClassInFileInspector;
    }


    public function validateEventHandlers(\Iterator $files)
    {
        $files = $this->filterFiles($files);

        foreach ($files as $file) {
            $fullFilePath = $file;

            $this->validateFile($fullFilePath);
        }
    }

    /**
     * @param string $filePath
     * @return bool
     */
    protected function isListenerFileName($filePath)
    {
        return preg_match('#\.php$#ims', $filePath);
    }

    /**
     * @param $fullFilePath
     */
    protected function validateFile($fullFilePath)
    {
        $fqn = $this->phpClassInFileInspector->getFullyQualifiedClassName($fullFilePath);

        if ($fqn !== null) {
            $this->validateEventHandlersInClass($fqn);
        }
    }

    protected function filterFiles(\Iterator $files)
    {
        return new \CallbackFilterIterator($files, function ($file) {
            return $this->isListenerFileName($file);
        });
    }

    /**
     * @param $className
     * @throws \Exception
     */
    private function validateEventHandlersInClass($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if (!$this->classValidator->isClassAccepted($reflectionClass)) {
            return;
        }

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {

            if (!$this->isValidListenerMethod($reflectionMethod)) {
                continue;
            }

            $eventClass = $this->getMessageClassFromMethod($reflectionMethod);

            if ($eventClass) {

                $validMethodName = $this->getMethodNameFromEventClass($eventClass);

                if ($reflectionMethod->name != $validMethodName) {
                    throw new \Exception("Method's name is invalid: {$reflectionMethod->name} for event $eventClass in\n" .
                        "{$reflectionClass->getFileName()}:{$reflectionMethod->getStartLine()}\n" .
                        "should be $validMethodName");
                }
            }
        }
    }

    private function getMessageClassFromMethod(\ReflectionMethod $reflectionMethod)
    {
        $reflectionParameter = $reflectionMethod->getParameters()[0];

        $typeHintedClass = $reflectionParameter->getClass();

        if ($typeHintedClass) {
            return $typeHintedClass->name;
        }

        throw new \Exception("Method parameter is not type hinted");
    }

    private function isValidListenerMethod(\ReflectionMethod $reflectionMethod)
    {
        if ($reflectionMethod->getNumberOfParameters() == 0) {
            return false;
        }

        return 0 === stripos($reflectionMethod->name, 'apply');
    }

    private function getMethodNameFromEventClass($className)
    {
        $parts = explode('\\', $className);

        return 'apply' . end($parts);
    }
}