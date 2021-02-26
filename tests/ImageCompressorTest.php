<?php

use PHPUnit\Framework\TestCase;
use tihiy\Compressor\compressors\Jpegoptim;
use tihiy\Compressor\compressors\Pngquant;
use tihiy\Compressor\ImageCompressor;

class ImageCompressorTest extends TestCase
{
    private $jpegFile;

    private $pngFile;

    private $gifFile;

    private $emptyContentFile;

    private $compressedJpegFile;

    private $compressedPngFile;

    public function setUp()
    {
        parent::setUp();

        $this->jpegFile = __DIR__ . '/examples/example.jpg';
        $this->pngFile = __DIR__ . '/examples/example2.png';
        $this->gifFile = __DIR__ . '/examples/example3.gif';
        $this->emptyContentFile = __DIR__ . '/examples/empty';

        $this->compressedJpegFile = __DIR__ . '/examples/compressed_example.jpg';
        $this->compressedPngFile = __DIR__ . '/examples/compressed_example2.png';
    }

    public function testSourceFileWithNotExistShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceFile('/examples/example4.png')->compress();
    }

    public function testSourceFileWithEmptyContentShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceFile($this->emptyContentFile)->compress();
    }

    public function testSourceFileWithNotValidMimeTypeShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceFile($this->gifFile)->compress();
    }

    public function testSourceFileWithoutFilePathShouldThrowArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);

        ImageCompressor::sourceFile()->compress();
    }

    public function testSourceFileWithErrorArgumentTypeShouldThrowTypeError()
    {
        $this->expectException(TypeError::class);

        ImageCompressor::sourceFile(null)->compress();
    }

    public function testSourceFileShouldReturnJpegoptim()
    {
        $this->assertInstanceOf(Jpegoptim::class, ImageCompressor::sourceFile($this->jpegFile));
    }

    public function testSourceFileShouldReturnPngquant()
    {
        $this->assertInstanceOf(Pngquant::class, ImageCompressor::sourceFile($this->pngFile));
    }

    public function testCompressJpegFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceFile($this->jpegFile)->compress($this->compressedJpegFile));
    }

    public function testCompressPngFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceFile($this->pngFile)->compress($this->compressedPngFile));
    }
}
