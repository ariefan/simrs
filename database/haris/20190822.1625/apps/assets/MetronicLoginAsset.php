<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MetronicLoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'metronic6/login/login-6.css',

        'metronic6/vendors/global/vendors.bundle.css',
        'metronic6/css/style.bundle.css',

        'metronic6/skins/header/base/light.css',
        'metronic6/skins/header/menu/light.css',
        'metronic6/skins/brand/dark.css',
        'metronic6/skins/aside/dark.css',
    ];
    public $js = [
        'metronic6/vendors/vendors.bundle.js',
        'metronic6/js/scripts.bundle.js',
        'metronic6/login/login-1.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
