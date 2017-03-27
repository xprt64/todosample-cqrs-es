<?php
/**
 * @copyright Constantin Galbenu gica.galbenu@gmail.com
 * All rights reserved.
 */

namespace Gica\FileSystem;


class LocalFileToUploadedFileAdapter extends \Zend\Diactoros\UploadedFile
    implements \Psr\Http\Message\UploadedFileInterface
{
    protected $shouldMove = false;

    protected $filePath;

    /**
     * @param boolean $shouldMove
     * @return static
     */
    public function setShouldMove($shouldMove)
    {
        $this->shouldMove = $shouldMove;
        return $this;
    }

    public function __construct($filePath, $fileName)
    {
        $this->filePath =   $filePath;

        parent::__construct($filePath, filesize($filePath), 0, $fileName);
    }

    public function moveTo($targetPath)
    {
        if($this->shouldMove)
            return rename($this->filePath, $targetPath);
        else
            return copy($this->filePath, $targetPath);
    }
}