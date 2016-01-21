<?php

/**
 * This file is part of the m1\twig-asset-rev-extension library
 *
 * (c) m1 <hello@milescroxford.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     m1/twig-asset-rev-extension
 * @version     0.1.0
 * @author      Miles Croxford <hello@milescroxford.com>
 * @copyright   Copyright (c) Miles Croxford <hello@milescroxford.com>
 * @license     http://github.com/m1/TwigAssetRevExtension/blob/master/LICENSE
 * @link        http://github.com/m1/TwigAssetRevExtension/blob/master/README.MD Documentation
 */

namespace M1\TwigAssetRevExtension;

/**
 * The TwigAssetRevExtension class
 *
 * @since 0.1.0
 */
class TwigAssetRevExtension extends \Twig_Extension
{
    /**
     * The file extensions to check if there is a minified version
     *
     * @var array
     */
    private static $minify_exts = array('css', 'js');

    /**
     * The array of assets rev, raw_asset => rev_asset
     *
     * @var array
     */
    private $assets;

    /**
     * Whether to search for minified rev'd versions of the assets
     *
     * @var bool
     */
    private $minified;

    /**
     * The TwigAssetRevExtension constructor
     *
     * @param array $assets   The array of assets and rev'd assets
     * @param bool  $minified Whether to search for minified rev'd assets
     */
    public function __construct(array $assets, $minified = true)
    {
        $this->assets = $assets;
        $this->minified = $minified;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('asset_rev', array($this, 'assetRev'), array('needs_environment' => true)),
        );
    }

    /**
     * Gets the rev'd asset,
     *
     * @param \Twig_Environment $env   The twig environment
     * @param string            $asset The asset string to rev
     *
     * @return string The rev'd asset if available, else the original asset
     */
    public function assetRev(\Twig_Environment $env, $asset)
    {
        $pathinfo = pathinfo($asset);

        if (!isset($pathinfo['extension'])) {
            return $asset;
        }

        return ($this->minify($env, $pathinfo)) ?: ((isset($this->assets[$asset])) ? $this->assets[$asset] : $asset);
    }

    /**
     * Gets the minified asset
     *
     * @param \Twig_Environment $env      The twig environment
     * @param array             $pathinfo The pathinfo for the asset
     *
     * @return bool|string The minified rev'd asset if available, else false
     */
    public function minify($env, $pathinfo)
    {
        if ($this->minified && !$env->isDebug() && in_array($pathinfo['extension'], self::$minify_exts)) {
            $min = sprintf(
                "%s/%s.min.%s",
                $pathinfo['dirname'],
                $pathinfo['filename'],
                $pathinfo['extension']
            );

            if (isset($this->assets[$min])) {
                return $this->assets[$min];
            }
        }

        return false;
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'asset_rev';
    }
}
