<?php

namespace tihiy\Compressor\Compressor;

/**
 * Class Pngquant.
 */
class Pngquant extends AbstractCompressor
{
    /**
     * {@inheritDoc}
     */
    protected function getCommand(string $sourceFilePath, string $compressedFilePath): string
    {
        $options = [
            '--force',
            '--skip-if-larger',
            '--speed 1',
            '--quality 85',
        ];

        return sprintf(
            "pngquant %s %s --output %s",
            $sourceFilePath,
            implode(' ', $options),
            $compressedFilePath
        );
    }
}
