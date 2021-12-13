<?php

namespace tihiy\Compressor\compressors;

/**
 * Class Pngquant.
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
 */
class Pngquant extends BaseCompressor
{
    /**
     * {@inheritDoc}
     */
    protected function getCommand(string $sourceFilePath, string $tempFilePath): string
    {
        $options = [
            '--force',
            '--skip-if-large',
            '--speed 1',
            '--quality 85',
        ];

        return sprintf(
            "pngquant %s %s --output %s",
            $sourceFilePath,
            implode(' ', $options),
            $tempFilePath
        );
    }
}
