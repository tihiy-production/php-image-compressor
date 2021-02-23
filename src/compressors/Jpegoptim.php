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

            $params = [
                '--strip-com',
                '--strip-iptc',
                '--strip-icc',
                '--strip-xmp',
                '--all-progressive',
                '--quiet',
                '--max=85',
            ];

            if (filesize($this->getSourcePath()) > 100000) {
                $params[] = '-S40%%';
            }

            $command = sprintf(
                "jpegoptim %s --stdout %s > %s",
                implode(' ', $params),
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
