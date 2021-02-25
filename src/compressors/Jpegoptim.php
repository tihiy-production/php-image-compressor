<?php

namespace tihiy\Compressor\compressors;

use ErrorException;

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
     * Try to optimize file to given size as percentage of the original file size
     */
    private const DEFAULT_SIZE = 90;

    private const MAX_SIZE = 40;

    /**
     * {@inheritDoc}
     */
    protected function getCommand(string $sourcePath, string $tempFilePath): string
    {
        $options = [
            '--force',
            '--strip-com',
            '--strip-iptc',
            '--strip-icc',
            '--strip-xmp',
            '--all-progressive',
            '--quiet',
            '--max=85',
        ];

        $size = self::DEFAULT_SIZE;
        if ($this->isCompressionAvailable()) {
            $size = self::MAX_SIZE;
        }

        $options[] = "-S{$size}%%";

        return sprintf(
            "jpegoptim %s --stdout %s > %s",
            implode(' ', $options),
            $sourcePath,
            $tempFilePath
        );
    }

    /**
     * Checking for file compression availability
     *
     * @return bool
     *
     * @throws ErrorException
     */
    private function isCompressionAvailable(): bool
    {
        $bufferFile = $this->fileConfigurator->createTemporaryFile($this->getSourceFileData());

        $output = null;
        exec(sprintf("jpegoptim --strip-all --all-progressive %s", $bufferFile), $output);
        $output = $output[0] ?? '';

        $fromBytes = null;
        $toBytes = null;
        if (1 === preg_match('/(\d+) --> (\d+)/', $output, $matches)) {
            $fromBytes = isset($matches[1]) ? (float)$matches[1] : null;
            $toBytes = isset($matches[1]) ? (float)$matches[2] : null;
        }

        return (($fromBytes && $toBytes) && ($toBytes > $fromBytes));
    }
}
