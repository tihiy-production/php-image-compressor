<?php

namespace tihiy\Compressor;

use tihiy\Compressor\Assertions\FileAssertions;
use tihiy\Compressor\Compressor\CompressorInterface;
use tihiy\Compressor\Factory\CompressorFactory;
use tihiy\Compressor\Object\File;
use tihiy\Compressor\Service\FileConfigurator;

class ImageCompressor
{
    protected $compressor;
    protected $sourceFile;

    protected function __construct(CompressorInterface $compressor, File $sourceFile)
    {
        $this->compressor = $compressor;
        $this->sourceFile = $sourceFile;
    }

    public static function sourceFile(string $path): self
    {
        FileAssertions::assertExist($path);
        FileAssertions::assertSize($path);

        $file = File::createFromFile($path);
        $compressor = CompressorFactory::create($file);

        return new self($compressor, $file);
    }

    public static function sourceContent(string $content): self
    {
        $file = File::createFromContent($content);
        $compressor = CompressorFactory::create($file);

        return new self($compressor, $file);
    }

    public static function sourceUrl(string $url): self
    {
        FileAssertions::assertUrl($url);
        FileAssertions::assertImage($url);

        $file = File::createFromUrl($url);
        $compressor = CompressorFactory::create($file);

        return new self($compressor, $file);
    }

    public function toFile(string $outputPath): bool
    {
        $compressedFile = $this->compressor->compress($this->sourceFile);

        return false !== file_put_contents($outputPath, $compressedFile->getContent());
    }

    public function toContent(): string
    {
        return $this->compressor->compress($this->sourceFile)->getContent();
    }

    public function __destruct()
    {
        FileConfigurator::removeTemporaryFiles();
    }
}
