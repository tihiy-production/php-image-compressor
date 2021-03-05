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
            "-S{$this->getCompressionSize()}%%",
        ];

        return sprintf(
            "jpegoptim %s --stdout %s > %s",
            implode(' ', $options),
            $sourceFilePath,
            $tempFilePath
        );
    }

    /**
     * Get the percentage of file compression from the original file size
     *
     * @return int
     *
     * @throws ErrorException
     */
    private function getCompressionSize(): int
    {
        return $this->isCompressionAvailable() ? self::MAX_SIZE : self::DEFAULT_SIZE;
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
        $compressedTempFilePath = $this->fileConfigurator->createTemporaryFile($this->getSourceFileContent());

        $command = sprintf(
            "jpegoptim --force --strip-all --all-progressive --quiet --max=85 %s",
            $this->systemCommand->getEscapedFilePath($compressedTempFilePath)
        );

        if (!$this->systemCommand->execute($command)->isSuccess()) {
            return false;
        }

        return FileConfigurator::getFileSize($this->getSourceTempFilePath()) > FileConfigurator::getFileSize($compressedTempFilePath);
    }
}
