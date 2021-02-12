<?php

namespace tihiy\Compressor;

use ErrorException;
use tihiy\Compressor\compressors\BaseCompressor;
use tihiy\Compressor\compressors\Jpegoptim;
use tihiy\Compressor\compressors\Pngquant;

/**
 * Class ImageCompressor.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
 */
class ImageCompressor
{
    /**
     * MIME-types
     */
    protected const MIME_TYPE_PNG = 'image/png';

    protected const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * Get an object based on the data of the uploaded file
     *
     * @param string $path The path to the file to be compressed
     *
     * @return BaseCompressor
     *
     * @throws ErrorException
     */
    public static function sourceFile(string $path): BaseCompressor
    {
        if (!file_exists($path)) {
            throw new ErrorException('The file does not exist');
        }

        $mimeType = mime_content_type($path);

        if (!self::allowCompression($mimeType)) {
            throw new ErrorException("Compression is not available for '{$mimeType}' MIME-type");
        }

        switch ($mimeType) {
            case self::MIME_TYPE_PNG:
                return new Pngquant($path);
            default:
                return new Jpegoptim($path);
        }
    }

    /**
     * @param string $mimeType
     *
     * @return bool
     */
    protected static function allowCompression(string $mimeType): bool
    {
        return in_array($mimeType, self::getAllowedMimeTypes(), true);
    }

    /**
     * @return array
     */
    protected static function getAllowedMimeTypes(): array
    {
        return [
            self::MIME_TYPE_PNG,
            self::MIME_TYPE_JPEG,
        ];
    }
}