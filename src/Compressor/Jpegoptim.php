<?php

namespace tihiy\Compressor\Compressor;

use ErrorException;
use tihiy\Compressor\Service\FileConfigurator;

/**
 * Class Jpegoptim.
 */
class Jpegoptim extends AbstractCompressor
{
    /**
     * Try to optimize file to given size as percentage of the original file size
     */
    private const DEFAULT_SIZE = 90;

    private const MAX_SIZE = 40;

    /**
     * @var array
     */
    private $options = [
        '--force',
        '--strip-com',
        '--strip-iptc',
        '--strip-icc',
        '--strip-xmp',
        '--all-progressive',
        '--quiet',
        '--max=85',
    ];

    /**
     * {@inheritDoc}
     */
    protected function getCommand(string $sourceFilePath, string $tempFilePath): string
    {
        $options = $this->options;
        $options[] = "-S{$this->getCompressionSize()}%%";

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
        $compressedTempFilePath = FileConfigurator::createTemporaryFile($this->getSourceFile()->getContent());

        $command = sprintf(
            "jpegoptim %s %s",
            implode(' ', $this->options),
            $this->systemCommand->getEscapedFilePath($compressedTempFilePath)
        );

        if (!$this->systemCommand->execute($command)->isSuccess()) {
            return false;
        }

        return $this->getSourceFile()->getSize() > filesize($compressedTempFilePath);
    }
}
