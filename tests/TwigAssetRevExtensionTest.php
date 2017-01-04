<?php

namespace M1\TwigAssetRevExtension\Test;

class TwigAssetRevExtensionTest extends \PHPUnit_Framework_TestCase
{
    private function loadJson($file)
    {
        return json_decode(file_get_contents(__DIR__.'/stub/'.$file), true);
    }

    private function makeAssetTwigString($asset)
    {
        return sprintf('{{ "%s"|asset_rev }}', $asset);
    }

    public function testStandard()
    {
        $expected_assets = array(
            "css/app.css" => "css/app.min.9f8d3d255c1f.css",
            "js/app.admin.js" => "js/app.admin.min.dbdc6d8e2114.js",
            "js/app.admin.plugins.js" => "js/app.admin.plugins.min.283a1a903f4a.js",
            "img/image-jpg.jpg" => "img/image-jpg.219a48cfe072.jpg",
            "img/image-png.png" => "img/image-png.1691620d298a.png",
            "img/image-gif.gif" => "img/image-gif.bcd9f17c5cf8.png"
        );

        $loader_array = array();

        foreach (array_keys($expected_assets) as $raw_asset) {
            $loader_array[$raw_asset] = $this->makeAssetTwigString($raw_asset);
        }

        $loader = new \Twig_Loader_Array($loader_array);

        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($this->loadJson('rev-manifest.json')));

        foreach ($expected_assets as $raw_asset => $rev_asset) {
            $this->assertEquals($rev_asset, $twig->render($raw_asset));
        }
    }

    public function testFileAsArgument()
    {
        $expected_assets = array(
            "css/app.css" => "css/app.min.9f8d3d255c1f.css",
            "js/app.admin.js" => "js/app.admin.min.dbdc6d8e2114.js",
            "js/app.admin.plugins.js" => "js/app.admin.plugins.min.283a1a903f4a.js",
            "img/image-jpg.jpg" => "img/image-jpg.219a48cfe072.jpg",
            "img/image-png.png" => "img/image-png.1691620d298a.png",
            "img/image-gif.gif" => "img/image-gif.bcd9f17c5cf8.png"
        );

        $loader_array = array();

        foreach (array_keys($expected_assets) as $raw_asset) {
            $loader_array[$raw_asset] = $this->makeAssetTwigString($raw_asset);
        }

        $loader = new \Twig_Loader_Array($loader_array);

        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension(__DIR__.'/stub/rev-manifest.json'));

        foreach ($expected_assets as $raw_asset => $rev_asset) {
            $this->assertEquals($rev_asset, $twig->render($raw_asset));
        }
    }

    public function testNoMinAsset()
    {
        $file = 'css/app.css';
        $expected = "css/app.bd6efcb01bc3.css";

        $assets = array(
            $file => $expected
        );

        $loader = new \Twig_Loader_Array(array($file => $this->makeAssetTwigString($file)));
        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets));

        $this->assertEquals($expected, $twig->render($file));
    }

    public function testMinAsset()
    {
        $file = 'css/app.css';
        $expected = "css/app.min.9f8d3d255c1f.cs";

        $assets = array(
            $file => "css/app.bd6efcb01bc3.css",
            "css/app.min.css" => $expected,
        );

        $loader = new \Twig_Loader_Array(array(
            $file => $this->makeAssetTwigString($file)
        ));

        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets));

        $this->assertEquals($expected, $twig->render($file));
    }

    public function testOverrideMin()
    {
        $file = 'css/app.css';
        $expected = "css/app.min.9f8d3d255c1f.cs";

        $assets = array(
            $file => "css/app.bd6efcb01bc3.css",
            "css/app.min.css" => $expected,
        );

        $loader = new \Twig_Loader_Array(array(
            $file => $this->makeAssetTwigString($file)
        ));

        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets, false));

        $this->assertEquals($twig->render($file), $assets[$file]);
    }

    public function testOverrideMinDebug()
    {
        $file = 'css/app.css';
        $expected = "css/app.min.9f8d3d255c1f.cs";

        $assets = array(
            $file => "css/app.bd6efcb01bc3.css",
            "css/app.min.css" => $expected,
        );

        $loader = new \Twig_Loader_Array(array(
            $file => $this->makeAssetTwigString($file)
        ));

        $twig = new \Twig_Environment($loader, array(
            'debug' => true
        ));

        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets));

        $this->assertEquals($assets[$file], $twig->render($file));
    }

    public function testNoExtension()
    {
        $file = 'img/nofile';
        $assets = array(
          $file => 'nope'
        );

        $loader = new \Twig_Loader_Array(array(
            $file => $this->makeAssetTwigString($file)
        ));

        $twig = new \Twig_Environment($loader);

        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets));
        $this->assertEquals($file, $twig->render($file));
    }

    public function testEmptyAssets()
    {
        $file = 'img/nofile';
        $assets = array(
            $file => 'nope'
        );

        $loader = new \Twig_Loader_Array(array(
            $file => $this->makeAssetTwigString($file)
        ));

        $twig = new \Twig_Environment($loader);

        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension(array()));
        $this->assertEquals($file, $twig->render($file));
    }

    public function testNonExistentAsset()
    {
        $file = 'img/nofile';
        $otherfile = 'css/css.css';

        $assets = array(
            $file => 'nope'
        );

        $loader = new \Twig_Loader_Array(array(
            $otherfile => $this->makeAssetTwigString($otherfile)
        ));

        $twig = new \Twig_Environment($loader);

        $twig->addExtension(new \M1\TwigAssetRevExtension\TwigAssetRevExtension($assets));
        $this->assertEquals($otherfile, $twig->render($otherfile));
    }
}
