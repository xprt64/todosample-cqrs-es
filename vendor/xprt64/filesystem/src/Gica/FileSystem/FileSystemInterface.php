<?php
/**
 * @copyright  Copyright (c) Galbenu xprt64@gmail.com
 * All rights reserved.
 */

namespace Gica\FileSystem;

interface FileSystemInterface
{
    public function realPath($unrealPath);

    public function isDirectory($directoryPath);

    public function makeDirectory($directoryPath, $mode = 0777, $recursive = false, $context = null);

    public function fileDelete($filePath);

    public function fileExists($filePath);

    public function fileIsReadable($filePath);

    public function fileGetContents($filePath);

    public function filePutContents($fullStoragePath, $contents);

    public function isPathInsidePath($parentFolder, $subFolder);

    public function fileGetOwner($path);

    public function fileSetOwner($path, $owner);

    public function fileSetPermissions($path, $permissions);

    public function fileSetOwnerRecursive($basePath, $relativePath, $owner);

    /**
     * @param string $path
     * @param string $mode
     * @return \Psr\Http\Message\StreamInterface
     */
    public function fileGetStream($path, $mode):\Psr\Http\Message\StreamInterface;

    public function fileGetSize($path);

    public function md5File($path);

    public function fileOutput($path);

    public function fileWriteStream($fullStoragePath, \Psr\Http\Message\StreamInterface $stream);

    public function fileCopy($sourcePath, $destinationPath);
}