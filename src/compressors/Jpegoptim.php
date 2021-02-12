<?php

namespace tihiy\Compressor\compressors;

use Exception;

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
                "jpegoptim --force --strip-com --strip-iptc --strip-icc --strip-xmp --all-progressive --quiet --size=200 --max=85 --stdout %s > %s",
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
