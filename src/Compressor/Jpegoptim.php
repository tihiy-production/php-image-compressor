<?php

namespace tihiy\Compressor\Compressor;

use ErrorException;
use tihiy\Compressor\Object\File;

class Jpegoptim extends AbstractCompressor
{
    /**
     * Try to optimize file to given size as percentage of the original file size
     */
    protected const DEFAULT_COMPRESSION = 90;
    protected const MAX_COMPRESSION = 40;

    protected $options = [
        '--force',
        '--strip-com',
        '--strip-iptc',
        '--strip-icc',
        '--strip-xmp',
        '--all-progressive',
        '--quiet',
        '--max=85',
    ];

    public function compress(File $file): File
    {
        $compressedFile = File::createFromContent('');
        $command = $this->getCommand($file, $compressedFile);

        if ($this->executeCommand($command) && $compressedFile->getSize()) {
            return $compressedFile;
        }

        throw new ErrorException('Compression failed for JPEG file.');
    }

    protected function getCommand(File $sourceFile, File $compressedFile): string
    {
        $compressionSize = $this->getCompressionSize($sourceFile);

        return sprintf(
            "jpegoptim %s -S%d%% --stdout %s > %s",
            implode(' ', $this->options),
            $compressionSize,
            $this->systemCommand->getEscapedFilePath($sourceFile->getTempPath()),
            $this->systemCommand->getEscapedFilePath($compressedFile->getTempPath())
        );
    }

    protected function getCompressionSize(File $sourceFile): int
    {
        return $this->isCompressionAvailable($sourceFile) ? self::MAX_COMPRESSION : self::DEFAULT_COMPRESSION;
    }

    protected function isCompressionAvailable(File $sourceFile): bool
    {
        $tempFile = File::createFromContent($sourceFile->getContent());
        $command = sprintf(
            "jpegoptim %s %s",
            implode(' ', $this->options),
            $this->systemCommand->getEscapedFilePath($tempFile->getTempPath())
        );

        return $this->executeCommand($command) && $sourceFile->getSize() > $tempFile->getSize();
    }
}
