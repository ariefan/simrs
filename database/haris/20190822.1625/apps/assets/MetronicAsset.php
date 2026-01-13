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
class MetronicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'metronic/global/plugins/font-awesome/css/font-awesome.min.css',
        'metronic6/vendors/global/vendors.bundle.css',
        'metronic6/css/pages/style.bundle.css',
        
        'metronic6/skins/header/menu/light.css',
    ];
    public $js = [
        // 'metronic/global/plugins/bootstrap/js/bootstrap.min.js',
        'metronic6/vendors/vendors.bundle.js',
        'metronic6/js/scripts.bundle.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
