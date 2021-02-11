<?php

namespace tihiy\Compressor\compressors;

use Exception;

/**
 * Class Pngquant
 */
class Pngquant extends BaseCompressor
{
    /**
     * Default options
     *
     * All parameters available by entering the command "pngquant -h" in terminal
     *
     * @var array
     */
    protected $options = [
        '--force',
        '--quality 85',
    ];

    /**
     * {@inheritDoc}
     */
    public function compress(?string $path = null): bool
    {
        try {
            if (!$path) {
                $path = $this->path;
            }

            $tempFilePath = $this->createTemporaryFile();

            $command = sprintf(
                "pngquant %s %s --output %s",
                $this->getEscapedFilePath($this->path),
                $this->getOptions(),
                $this->getEscapedFilePath($tempFilePath)
            );

            if (!$this->executeCommand($command)) {
                return false;
            }

            return $this->saveFile($path, $tempFilePath);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Compression options
     *
     * @return string
     */
    protected function getOptions(): string
    {
        return str_replace(['pngquant', '--output'], ['', ''], parent::getOptions());
    }
}
