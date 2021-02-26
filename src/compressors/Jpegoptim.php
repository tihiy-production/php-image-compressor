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
    protected function getCommand(string $sourceFilePath, string $tempFilePath): string
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
            $sourceFilePath,
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
        $tempFilePath = $this->fileConfigurator->createTemporaryFile($this->getSourceFileData());
        $command = sprintf(
            "jpegoptim --strip-all --all-progressive %s",
            $this->systemCommand->getEscapedFilePath($tempFilePath)
        );
        $output = $this->systemCommand->execute($command)->getOutput();

        if (1 === preg_match('/(\d+) --> (\d+)/', $output, $matches)) {
            $fromBytes = $matches[1] ?? null;
            $toBytes = $matches[2] ?? null;

            return (($fromBytes && $toBytes) && ($fromBytes !== $toBytes));
        }

        return false;
    }
}
