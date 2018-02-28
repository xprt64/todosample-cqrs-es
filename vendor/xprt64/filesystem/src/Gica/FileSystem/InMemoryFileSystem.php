<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace Gica\FileSystem;


class InMemoryFileSystem implements \Gica\FileSystem\FileSystemInterface
{
    protected $files = [];
    protected $owners = [];
    protected $directories = [
        '/' => true,
    ];

    public function makeDirectory($directoryPath, $mode = 0777, $recursive = false, $context = null)
    {
        if ($recursive)
            $this->makeDirectoryRecursive($directoryPath);
        else
            $this->makeDirectoryNotRecursive($directoryPath);
    }

    private function makeDirectoryRecursive($directoryPath)
    {
        $realPath = $this->realPath($directoryPath);

        $parts = explode('/', $realPath);

        $currentPath = '';

        foreach ($parts as $part) {
            if ('' === $part)
                continue;
            $currentPath .= '/' . $part;
            $this->addDirectory($currentPath);
        }
    }

    public function realPath($unrealPath)
    {
        return $this->collapseSpecialDirectoryNames($unrealPath);
    }

    private function collapseSpecialDirectoryNames($unrealPath)
    {
        $unrealPath = str_replace('\\', '/', $unrealPath);
        $unrealPath = preg_replace('/[\x00-\x1F\x7F]/', '', $unrealPath);

        $parts = explode('/', $unrealPath);
        $result = [];
        foreach ($parts as $part) {
            switch ($part) {
                case '..':
                    array_pop($result);
                    break;
                case '':
                    break;
                case '.':
                    break;
                default:
                    array_push($result, $part);
            }
        }

        return '/' . implode('/', $result);
    }

    protected function addDirectory($realPath)
    {
        $this->directories[$realPath] = true;
    }

    protected function makeDirectoryNotRecursive($directoryPath)
    {
        $realPath = $this->realPath($directoryPath);
        if (!$this->isDirectory($this->getParentDirectory($realPath)))
            throw new \Gica\FileSystem\Exception($this->formatString("A parent directory of '%s' does not exist", $directoryPath));
        $this->addDirectory($realPath);
    }

    public function isDirectory($directoryPath)
    {
        $realPath = $this->realPath($directoryPath);
        return isset($this->directories[$realPath]);
    }

    public function getParentDirectory($path)
    {
        return $this->realPath($path . '/..');
    }

    protected function formatString($format, ...$parameters)
    {
        $escapedParameters = [];

        foreach ($parameters as $parameter)
            $escapedParameters[] = htmlentities($parameter);

        return sprintf($format, ...$escapedParameters);
    }

    public function filePutContents($fullStoragePath, $contents)
    {
        if (!$this->isDirectory($this->getParentDirectory($fullStoragePath)))
            throw new \Gica\FileSystem\Exception($this->formatString("A parent directory of '%s' does not exist", $fullStoragePath));

        $realPath = $this->realPath($fullStoragePath);
        $this->files[$realPath] = $contents;
        return strlen($contents);
    }

    public function fileDelete($filePath)
    {
        $realPath = $this->realPath($filePath);
        if (!isset($this->files[$realPath])) {
            throw new \Exception('File delete failed: file does not exist');
        }
        unset($this->files[$realPath]);
    }

    public function deleteDirectory($filePath)
    {
        $realPath = $this->realPath($filePath);
        unset($this->directories[$realPath]);
    }

    public function getFilesInDirectoryRecursive($directoryPath)
    {
        return $this->getItemsInListRecursive($this->files, $directoryPath);
    }

    protected function getItemsInListRecursive($itemList, $directoryPath, $includeSelf = false)
    {
        $result = [];

        foreach ($itemList as $itemPath => $_) {
            if (!$includeSelf && $this->isPathEqualWithPath($directoryPath, $itemPath))
                continue;
            if ($this->isPathInsidePath($directoryPath, $itemPath))
                $result[] = $itemPath;
        }

        return $result;
    }

    public function isPathEqualWithPath($path1, $path2)
    {
        $path2 = $this->collapseSpecialDirectoryNames($path2);
        $path1 = $this->collapseSpecialDirectoryNames($path1);

        return $path2 === $path1;
    }

    public function isPathInsidePath($parentFolder, $subFolder)
    {
        $subFolder = rtrim($this->realPath($subFolder), '/') . '/';
        $parentFolder = rtrim($this->realPath($parentFolder), '/') . '/';

        return 0 === strpos($subFolder, $parentFolder);
    }

    public function getDirectoriesInDirectoryRecursive($directoryPath)
    {
        return $this->getItemsInListRecursive($this->directories, $directoryPath, false);
    }

    public function getFilesInDirectory($directoryPath)
    {
        return $this->getItemsInList($this->files, $directoryPath);
    }

    protected function getItemsInList($itemList, $directoryPath)
    {
        $result = [];

        $realDirectoryPath = $this->realPath($directoryPath);

        foreach ($itemList as $itemPath => $_)
            if ($this->getParentDirectory($itemPath) == $realDirectoryPath)
                $result[] = $itemPath;

        return $result;
    }

    public function getDirectoriesInDirectory($directoryPath)
    {
        return $this->getItemsInList($this->directories, $directoryPath);
    }

    public function fileIsReadable($filePath)
    {
        return $this->fileExists($filePath);
    }

    public function fileExists($filePath)
    {
        $realPath = $this->realPath($filePath);
        return isset($this->files[$realPath]);
    }

    public function fileGetContents($filePath)
    {
        if (!$this->fileExists($filePath))
            throw new \Gica\FileSystem\Exception\FileReadError($this->formatString("File '%s' does not exist", $filePath));

        $realPath = $this->realPath($filePath);
        return $this->files[$realPath];
    }

    public function fileGetOwner($path)
    {
        $realPath = $this->realPath($path);
        return $this->owners[$realPath];
    }

    public function fileSetOwner($path, $owner)
    {
        $realPath = $this->realPath($path);
        $this->owners[$realPath] = $owner;
    }

    public function fileSetOwnerRecursive($basePath, $relativePath, $owner)
    {
        $relativePath = $this->collapseSpecialDirectoryNames($relativePath);
        $relativePath = trim($relativePath, '/');

        $basePath = $this->collapseSpecialDirectoryNames($basePath);
        $basePath = rtrim($basePath, '/');

        $components = explode('/', $relativePath);

        $dir = $basePath;

        foreach ($components as $component) {
            if ($component == '')
                continue;

            $dir .= '/' . $component;

            $this->fileSetOwner($dir, $owner);
        }
    }

    public function fileGetStream($path, $mode): \Psr\Http\Message\StreamInterface
    {
        if (!$this->fileExists($path))
            throw new \Gica\FileSystem\Exception\FileReadError($this->formatString("File '%s' does not exist", $path));

        $realPath = $this->realPath($path);

        return new \Gica\FileSystem\MutableStringStream($this->files[$realPath]);
    }

    public function fileWriteStream($fullStoragePath, \Psr\Http\Message\StreamInterface $stream)
    {
        // TODO: Implement fileWriteStream() method.
    }

    public function fileSetPermissions($path, $permissions)
    {
        // TODO: Implement setFilePermissions() method.
    }

    public function fileGetSize($path)
    {
        // TODO: Implement getFileSize() method.
    }

    public function md5File($path)
    {
        // TODO: Implement md5File() method.
    }

    public function fileOutput($path)
    {
        // TODO: Implement outputFile() method.
    }

    public function fileCopy($sourcePath, $destinationPath)
    {
        // TODO: Implement copyFile() method.
    }

    public function fileSetModifiedDate($path, \DateTimeImmutable $modifiedDate)
    {
        // TODO: Implement fileSetModifiedDate() method.
    }
}