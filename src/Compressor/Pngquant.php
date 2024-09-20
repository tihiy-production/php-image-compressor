<?php

namespace tihiy\Compressor\Compressor;

use ErrorException;
use tihiy\Compressor\Object\File;

class Pngquant extends AbstractCompressor
{
    protected $options = [
        '--force',
        '--skip-if-larger',
        '--speed 1',
        '--quality 85',
    ];

    public function compress(File $file): File
    {
        $compressedFile = File::createFromContent('');
        $command = $this->getCommand($file, $compressedFile);

        if ($this->executeCommand($command) && $compressedFile->getSize()) {
            return $compressedFile;
        }

        throw new ErrorException('Compression failed for PNG file.');
    }

    protected function getCommand(File $sourceFile, File $compressedFile): string
    {
        return sprintf(
            "pngquant %s %s --output %s",
            $this->systemCommand->getEscapedFilePath($sourceFile->getTempPath()),
            implode(' ', $this->options),
            $this->systemCommand->getEscapedFilePath($compressedFile->getTempPath())
        );
    }
}
