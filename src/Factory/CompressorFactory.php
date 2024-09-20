<?php

namespace tihiy\Compressor\Factory;

use ErrorException;
use tihiy\Compressor\Compressor\CompressorInterface;
use tihiy\Compressor\Compressor\Jpegoptim;
use tihiy\Compressor\Compressor\Pngquant;
use tihiy\Compressor\Object\File;

class CompressorFactory
{
    /**
     * Supported MIME-types
     */
    protected const MIME_TYPE_PNG = 'image/png';
    protected const MIME_TYPE_JPEG = 'image/jpeg';

    public static function create(File $file): CompressorInterface
    {
        switch ($file->getMimeType()) {
            case self::MIME_TYPE_JPEG:
                return new Jpegoptim();
            case self::MIME_TYPE_PNG:
                return new Pngquant();
            default:
                throw new ErrorException('Unsupported MIME type.');
        }
    }
}
