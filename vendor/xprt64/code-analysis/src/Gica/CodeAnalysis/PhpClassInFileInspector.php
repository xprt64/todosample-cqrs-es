<?php


namespace Gica\CodeAnalysis;


use Gica\FileSystem\FileSystemInterface;
use Gica\FileSystem\OperatingSystemFileSystem;

class PhpClassInFileInspector
{
    /**
     * @var FileSystemInterface
     */
    private $fileSystem;

    public function __construct(
        FileSystemInterface $fileSystem = null
    )
    {
        $this->fileSystem = $fileSystem ?? new OperatingSystemFileSystem();
    }

    /**
     * @param $fullFilePath
     * @return null|string
     */
    public function getFullyQualifiedClassName(string $fullFilePath)
    {
        $content = $this->readFile($fullFilePath);

        if (!preg_match('#class\s+(?P<className>\S+)\s#ims', $content, $m)) {
            return null;
        }

        $unqualifiedClassName = $m['className'];

        if (!preg_match('#namespace\s+(?P<namespace>\S+);#ims', $content, $m)) {
            return null;
        }

        $namespace = $m['namespace'];
        if ($namespace)
            $namespace = '\\' . $namespace;


        $fqn = $namespace . '\\' . $unqualifiedClassName;

        if (!class_exists($fqn)) {
            $this->evaluateCode($content);
        }

        return $fqn;
    }

    private function readFile($fullFilePath)
    {
        return $this->fileSystem->fileGetContents($fullFilePath);
    }

    private function evaluateCode($content)
    {
        $content = str_replace('<?php', '', $content);
        eval($content);
    }

}