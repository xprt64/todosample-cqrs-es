<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\FileSystem;


use Gica\FileSystem\Exception\FileReadError;
use Gica\Xss\HtmlString;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Stream;

class OperatingSystemFileSystem implements FileSystemInterface
{
    public function realPath($unrealPath)
    {
        return realpath($unrealPath);
    }

    public function isDirectory($directoryPath)
    {
        return is_dir($directoryPath);
    }

    public function makeDirectory($directoryPath, $mode = 0777, $recursive = false, $context = null)
    {
        $parameters = [$directoryPath, $mode, $recursive];
        if (null !== $context)
            array_push($parameters, $context);

        $ok = mkdir(...$parameters);
        if (false === $ok)
            throw new Exception('Directory ' . $directoryPath . ' create failed:' . error_get_last()['message']);

        if (!$this->isDirectory($directoryPath))
            throw new Exception('Directory create failed: directory does not exists after creation');
    }

    public function filePutContents($fullStoragePath, $contents)
    {
        $ok = file_put_contents($fullStoragePath, $contents);
        if (false === $ok) {
            throw new Exception(sprintf("File %s save error:%s", $fullStoragePath, error_get_last()['message']));
        }
        return $ok;
    }

    public function fileWriteStream($fullStoragePath, StreamInterface $stream)
    {
        $h = fopen($fullStoragePath, "w+");

        if (!is_resource($h)) {
            throw new Exception(new HtmlString("Could not open %s file for writing", $fullStoragePath));
        }

        $stream->rewind();

        while (!$stream->eof()) {
            $data = $stream->read(1000);

            fwrite($h, $data);
        }

        fclose($h);
    }

    public function fileDelete($filePath)
    {
        $ok = unlink($filePath);
        if (!$ok)
        {
            throw new Exception('File delete failed');
        }
    }

    public function fileExists($filePath)
    {
        return file_exists($filePath);
    }

    public function fileIsReadable($filePath)
    {
        return is_readable($filePath);
    }

    public function fileGetContents($filePath)
    {
        if (!$this->fileExists($filePath))
            throw new FileReadError('File does not exist');

        $contents = file_get_contents($filePath);

        if (false === $contents)
            throw new FileReadError('File read error');
        return $contents;
    }


    public function isPathInsidePath($parentFolder, $subFolder)
    {
        $subFolder = rtrim($this->collapseSpecialDirectoryNames($subFolder), '/') . '/';
        $parentFolder = rtrim($this->collapseSpecialDirectoryNames($parentFolder), '/') . '/';

        return 0 === strpos($subFolder, $parentFolder);
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

    public function fileGetOwner($path)
    {
        return posix_getpwuid(fileowner($path));
    }

    public function fileSetOwner($path, $owner)
    {
        return chown($path, $owner);
    }

    public function fileSetModifiedDate($path, \DateTimeImmutable $modifiedDate)
    {
        return touch($path, $modifiedDate->getTimestamp());
    }

    public function fileSetPermissions($path, $permissions)
    {
        return chmod($path, $permissions);
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

    /**
     * @inheritdoc
     */
    public function fileGetStream($path, $mode): StreamInterface
    {
        $resource = fopen($path, $mode);
        if (false === $resource)
            throw new Exception(new HtmlString("File open '%s' in mode %s failed", $path, $mode));

        return new Stream($resource);
    }

    public function fileGetSize($path)
    {
        return filesize($path);
    }

    public function md5File($path)
    {
        return md5_file($path);
    }

    public function fileOutput($path)
    {
        return readfile($path);
    }

    public function fileCopy($sourcePath, $destinationPath)
    {
        $res = copy($sourcePath, $destinationPath);

        if (false === $res) {
            throw new Exception(new HtmlString("Could not copy %s to %s: %s", $sourcePath, $destinationPath, error_get_last()['message']));
        }
    }
}