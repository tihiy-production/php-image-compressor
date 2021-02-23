<?php

namespace tihiy\Compressor\compressors;

use Exception;

/**
 * Class Pngquant.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
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

            $result = true;
            if (!$this->systemCommand->execute($command)) {
                $result = false;
            }

            if (!$this->saveFile($path, $tempFilePath)) {
                $result = false;
            }

            if ($result === false) {
                return copy($this->getSourcePath(), $path);
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
