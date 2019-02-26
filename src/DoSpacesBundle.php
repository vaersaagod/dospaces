<?php
/**
 * @link https://www.vaersaagod.no/
 * @copyright Copyright (c) Værsågod
 * @license MIT
 */

namespace vaersaagod\dospaces;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Asset bundle for the Dashboard
 */
class DoSpacesBundle extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@vaersaagod/dospaces/resources';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/editVolume.js',
        ];

        parent::init();
    }
}
