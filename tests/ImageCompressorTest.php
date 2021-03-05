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

        ImageCompressor::sourceFile('/examples/example4.png')->toFile('/examples/example4.png');
    }

    public function testSourceFileWithEmptyContentShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceFile($this->emptyContentFile)->toFile($this->emptyContentFile);
    }

    public function testSourceFileWithNotValidMimeTypeShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceFile($this->gifFile)->toFile($this->gifFile);
    }

    public function testSourceFileWithoutArgumentsShouldThrowArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);

        ImageCompressor::sourceFile()->toFile();
    }

    public function testSourceFileWithErrorArgumentTypeShouldThrowTypeError()
    {
        $this->expectException(TypeError::class);

        ImageCompressor::sourceFile(null)->toFile(null);
    }

    public function testSourceFileShouldReturnJpegoptim()
    {
        $this->assertInstanceOf(Jpegoptim::class, ImageCompressor::sourceFile($this->jpegFile));
    }

    public function testSourceFileShouldReturnPngquant()
    {
        $this->assertInstanceOf(Pngquant::class, ImageCompressor::sourceFile($this->pngFile));
    }

    public function testSourceFileCompressJpegFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceFile($this->jpegFile)->toFile($this->compressedJpegFile));
    }

    public function testSourceFileCompressPngFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceFile($this->pngFile)->toFile($this->compressedPngFile));
    }

    public function testSourceContentNotValidFileContentShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceContent('content')->toContent();
    }

    public function testSourceContentWithEmptyContentShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceContent('')->toContent();
    }

    public function testSourceContentWithoutArgumentsShouldThrowArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);

        ImageCompressor::sourceContent()->toContent();
    }

    public function testSourceContentWithErrorArgumentTypeShouldThrowTypeError()
    {
        $this->expectException(TypeError::class);

        ImageCompressor::sourceContent(null)->toFile(null);
    }

    public function testSourceContentShouldReturnJpegoptim()
    {
        $this->assertInstanceOf(Jpegoptim::class, ImageCompressor::sourceContent(file_get_contents($this->jpegFile)));
    }

    public function testSourceContentShouldReturnPngquant()
    {
        $this->assertInstanceOf(Pngquant::class, ImageCompressor::sourceContent(file_get_contents($this->pngFile)));
    }

    public function testSourceContentCompressJpegFileToFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceContent(file_get_contents($this->jpegFile))->toFile($this->compressedJpegFile));
    }

    public function testSourceContentCompressPngFileToFileShouldReturnSuccessResult()
    {
        $this->assertTrue(ImageCompressor::sourceContent(file_get_contents($this->pngFile))->toFile($this->compressedPngFile));
    }

    public function testSourceContentCompressJpegToContentShouldReturnSuccessResult()
    {
        $this->assertInternalType(
            'string',
            ImageCompressor::sourceContent(file_get_contents($this->jpegFile))->toContent()
        );
    }

    public function testSourceContentCompressPngToContentShouldReturnSuccessResult()
    {
        $this->assertInternalType(
            'string',
            ImageCompressor::sourceContent(file_get_contents($this->pngFile))->toContent()
        );
    }

    public function testSourceUrlNotValidFileUrlShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceUrl('example.com/test.jpg')->toFile($this->jpegFile);
    }

    public function testSourceUrlWithEmptyUrlShouldThrowErrorException()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceUrl('')->toFile($this->jpegFile);
    }

    public function testSourceUrlWithoutArgumentsShouldThrowArgumentCountError()
    {
        $this->expectException(ArgumentCountError::class);

        ImageCompressor::sourceUrl()->toFile();
    }

    public function testSourceUrlWithErrorFileTypeShouldThrowTypeError()
    {
        $this->expectException(ErrorException::class);

        ImageCompressor::sourceUrl('http://www.africau.edu/images/default/sample.pdf')->toFile($this->jpegFile);
    }

    public function testSourceUrlWithErrorArgumentTypeShouldThrowTypeError()
    {
        $this->expectException(TypeError::class);

        ImageCompressor::sourceUrl(null)->toFile(null);
    }

    public function testSourceUrlShouldReturnJpegoptim()
    {
        $this->assertInstanceOf(
            Jpegoptim::class,
            ImageCompressor::sourceUrl('https://raw.githubusercontent.com/tihiy-production/php-image-compressor/master/tests/examples/example.jpg')
        );
    }

    public function testSourceUrlShouldReturnPngquant()
    {
        $this->assertInstanceOf(
            Pngquant::class,
            ImageCompressor::sourceUrl('https://raw.githubusercontent.com/tihiy-production/php-image-compressor/master/tests/examples/example2.png')
        );
    }

    public function testSourceUrlCompressJpegFileToFileShouldReturnSuccessResult()
    {
        $this->assertTrue(
            ImageCompressor::sourceUrl('https://raw.githubusercontent.com/tihiy-production/php-image-compressor/master/tests/examples/example.jpg')
                ->toFile($this->compressedJpegFile)
        );
    }

    public function testSourceUrlCompressPngFileToFileShouldReturnSuccessResult()
    {
        $this->assertTrue(
            ImageCompressor::sourceUrl('https://raw.githubusercontent.com/tihiy-production/php-image-compressor/master/tests/examples/example2.png')
                ->toFile($this->compressedPngFile)
        );
    }
}
