<?php

namespace dlds\imageable\bundles;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * This is just an example.
 */
class CoreAsset extends AssetBundle {

    public $sourcePath = '@dlds/imageable/assets';
    public $js = [
        'jquery.iframe-transport.js',
        'jquery.imageable.js',
            // 'jquery.iframe-transport.min.js',
            // 'jquery.imageAttachment.min.js',
    ];
    public $css = [
        'imageable.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
