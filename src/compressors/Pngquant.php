<?php

namespace tihiy\Compressor\compressors;

use Exception;

/**
 * Class Pngquant
 */
class Pngquant extends BaseCompressor
{
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
                "pngquant %s --force --skip-if-large --speed 1 --quality 85 --output %s",
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
}
