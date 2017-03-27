<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\CodeAnalysis;


use Gica\CodeAnalysis\MethodListenerDiscovery\ListenerClassValidator;
use Gica\CodeAnalysis\Shared\ClassSorter;
use Gica\CodeAnalysis\Traits\FilesInDirectoryExtracter;

class ClassDiscovery
{
    use FilesInDirectoryExtracter;

    protected $discoveredClasses = [];

    /** @var ListenerClassValidator */
    private $classValidator;
    /**
     * @var \Gica\CodeAnalysis\Shared\ClassSorter
     */
    private $classSorter;
    /**
     * @var PhpClassInFileInspector
     */
    private $phpClassInFileInspector;

    public function __construct(
        ListenerClassValidator $classValidator,
        ClassSorter $classSorter,
        PhpClassInFileInspector $phpClassInFileInspector = null
    )
    {
        $this->classValidator = $classValidator;
        $this->classSorter = $classSorter;
        $this->phpClassInFileInspector = $phpClassInFileInspector ?? new PhpClassInFileInspector;
    }


    public function discover($directory)
    {
        $files = $this->getFilesInDirectory($directory);

        $files = $this->filterFiles($files);

        foreach ($files as $file) {
            $fullFilePath = $file;

            $extractedClass = $this->extractClassFromFileIfAccepted($fullFilePath);

            if ($extractedClass) {
                $this->discoveredClasses[] = $extractedClass;
            }
        }

        $this->discoveredClasses = $this->sort($this->discoveredClasses);
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
     * @return bool|\ReflectionClass
     */
    protected function extractClassFromFileIfAccepted($fullFilePath)
    {
        $fqn = $this->phpClassInFileInspector->getFullyQualifiedClassName($fullFilePath);

        if (null === $fqn) {
            return false;
        }

        return $this->getClassIfAccepted($fqn);
    }

    /**
     * @return \ReflectionClass[]
     */
    public function getDiscoveredClasses()
    {
        return $this->discoveredClasses;
    }

    protected function filterFiles(array $files)
    {
        return array_filter($files, function ($file) {
            return $this->isListenerFileName($file);
        });
    }

    /**
     * @param $className
     * @return \ReflectionClass|null
     */
    private function getClassIfAccepted($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if ($this->classValidator->isClassAccepted($reflectionClass)) {
            return $reflectionClass;
        }

        return null;
    }

    /**
     * @param \ReflectionClass[] $discoveredClasses
     * @return \ReflectionClass[]
     */
    private function sort($discoveredClasses)
    {
        usort($discoveredClasses, $this->classSorter);

        return $discoveredClasses;
    }
}