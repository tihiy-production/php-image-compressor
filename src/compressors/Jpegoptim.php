<?php

namespace tihiy\Compressor\compressors;

use Exception;

/**
 * Class Pngquant
 */
class Jpegoptim extends BaseCompressor
{
    /**
     * Default options
     *
     * All parameters available by entering the command "jpegoptim -h" in terminal
     *
     * @var array
     */
    protected $options = [
        '--force',
        '--size=200',
        '--max=85',
    ];

    /**
     * {@inheritDoc}
     */
    public function compress(?string $path = null): bool
    {
        try {
            if (!$path) {
                $path = $this->getSourcePath();
            }

            $tempFilePath = $this->fileConfigurator->createTemporaryFile();

            $command = sprintf(
                "jpegoptim %s --stdout %s > %s",
                $this->getOptions(),
                $this->systemCommand->getEscapedFilePath($this->getSourcePath()),
                $this->systemCommand->getEscapedFilePath($tempFilePath)
            );

            if (!$this->systemCommand->execute($command)) {
                return false;
            }

            return $this->saveFile($path, $tempFilePath);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @return string
     */
    protected function getOptions(): string
    {
        return str_replace(['jpegoptim', '--stdout'], ['', ''], parent::getOptions());
    }
}
