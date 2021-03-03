# Compress images

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)

**ImageCompressor** - this is an easy way to compress images on the fly.

## Requirements

The following libraries need to be installed:

### Jpegoptim

```
sudo apt-get -y install jpegoptim
```

### Pngquant

```
sudo apt-get -y install pngquant
```

## Installation

Execute the following command to install this package as a dependency in your project:

```bash
composer require "tihiy-production/php-image-compressor"
```

## Usage

You can choose a local file as the source and write it to another file.
```php
tihiy\Compressor\ImageCompressor::sourceFile('uncompressed.jpg')->toFile('compressed.jpg');
```

You can upload an image content as the source and get the compressed image data.
```php
$sourceData = file_get_contents('uncompressed.jpg');
$resultData = tihiy\Compressor\ImageCompressor::sourceContent($sourceData)->toContent();
```

You can specify URL as a source to the image and compress it without having to upload.
```php
tihiy\Compressor\ImageCompressor::sourceUrl('https://example.com/uncompressed.jpg')->toFile('compressed.jpg');
```

## License

This software is licensed under the MIT License. [View the license](LICENSE.md).

[ico-version]: https://img.shields.io/packagist/v/tihiy-production/php-image-compressor.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-downloads]: https://img.shields.io/packagist/dt/tihiy-production/php-image-compressor.svg

[link-packagist]: https://packagist.org/packages/tihiy-production/php-image-compressor
[link-downloads]: https://packagist.org/packages/tihiy-production/php-image-compressor
