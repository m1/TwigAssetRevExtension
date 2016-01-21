# TwigAssetRevExtension

[![Author](http://img.shields.io/badge/author-@milescroxford-blue.svg?style=flat-square)](https://twitter.com/milescroxford)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Twig Asset Rev Extension adds a `asset_rev` twig filter so you can use asset revisioning from files, perfect for use 
and tested with [`gulp-rev`](https://github.com/sindresorhus/gulp-rev)/[`gulp-rev-all`](https://github.com/smysnk/gulp-rev-all) 
or [`grunt-rev`](https://github.com/cbas/grunt-rev)

## Requirements

`TwigAssetRevExtension` requires PHP version `5.3+`.

## Install

Via Composer

``` bash
$ composer require m1/twig-asset-rev-extension
```

## Usage

`TwigAssetRevExtension` works like other twig extensions, just add the extension using `$twig->addExtension()`. 

``` php
Use \M1\TwigAssetRevExtension\TwigAssetRevExtension;

$assets = json_decode(file_get_contents('rev-manifest.json'), true);
$asset_rev = new TwigAssetRevExtension($assets);
$twig->addExtension($asset_rev);
```

`example.twig`:

```twig
<link href='{{"css/app.css"|asset_rev}}' rel='stylesheet'>
```

`rev-manifest.json`:
```json
{
  "css/app.css": "css/app.bd6efcb01bc3.css",
  "css/app.min.css": "css/app.min.9f8d3d255c1f.css",
}
```

## Setup

```php
new TwigAssetRevExtension(array $assets [, bool $minified = true ] )
```

#### Parameters
##### assets
The array of assets and rev'd assets, an example:

```php
array(
  "css/app.css" => "css/app.bd6efcb01bc3.css",
  "css/app.min.css" => "css/app.min.9f8d3d255c1f.css",
  "js/app.admin.js" => "js/app.admin.96b3cc15df52.js",
  "js/app.admin.min.js" => "js/app.admin.min.dbdc6d8e2114.js",
  "js/app.admin.plugins.js" => "js/app.admin.plugins.927a9b50dd18.js",
  "js/app.admin.plugins.min.js" => "js/app.admin.plugins.min.283a1a903f4a.js",
  "img/image-jpg.jpg" => "img/image-jpg.219a48cfe072.jpg",
  "img/image-png.png" => "img/image-png.1691620d298a.png",
  "img/image-gif.gif" => "img/image-gif.bcd9f17c5cf8.png"
)
```

If using `gulp-rev` or `gulp-rev-all` this is just the contents of `rev-manifest.json` - parsing the json file with
`json_decode(file_get_contents('rev-manifest.json'), true);`. You should probably cache this result though so you don't have to 
read the file every request.

##### minified

When `true` this means that `TwigAssetRevExtension` will pass back minified assets if they're available. Twig debug mode 
or Silex debug mode override this and it won't pass back minified assets in development.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@milescroxford.com instead of using the issue tracker.

## Credits

- [Miles Croxford][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/m1/twig-asset-rev-extension.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/m1/TwigAssetRevExtension/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/m1/TwigAssetRevExtension.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/m1/TwigAssetRevExtension.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/m1/twig-aset-rev-extension.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/m1/twig-asset-rev-extension
[link-travis]: https://travis-ci.org/m1/TwigAssetRevExtension
[link-scrutinizer]: https://scrutinizer-ci.com/g/m1/TwigAssetRevExtension/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/m1/TwigAssetRevExtension
[link-downloads]: https://packagist.org/packages/m1/TwigAssetRevExtension
[link-author]: https://github.com/m1
[link-contributors]: ../../contributors
