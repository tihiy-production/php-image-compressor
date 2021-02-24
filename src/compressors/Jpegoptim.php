<?php

namespace tihiy\Compressor\compressors;

use ErrorException;
use tihiy\Compressor\compressors\components\FileConfigurator;

/**
 * Class Jpegoptim.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
 */
class Jpegoptim extends BaseCompressor
{
    /**
     * The minimum file size of bytes for which to set the size compression setting
     */
    private const MIN_FILE_SIZE = 150000;

    /**
     * {@inheritDoc}
     */
    protected function getCommand(string $sourcePath, string $tempFilePath): string
    {
        $options = [
            '--strip-com',
            '--strip-iptc',
            '--strip-icc',
            '--strip-xmp',
            '--all-progressive',
            '--quiet',
            '--max=85',
        ];

        if ($this->isEnableSizeOption()) {
            $options[] = '-S40%%';
        }

        return sprintf(
            "jpegoptim %s --stdout %s > %s",
            implode(' ', $options),
            $sourcePath,
            $tempFilePath
        );
    }

    /**
     * @return bool
     *
     * @throws ErrorException
     */
    private function isEnableSizeOption(): bool
    {
        return FileConfigurator::getFileSize($this->getSourcePath()) > self::MIN_FILE_SIZE;
    }
}
