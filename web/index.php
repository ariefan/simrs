<?php
//date_default_timezone_set('Asia/Ujung_Pandang');
// comment out the following two lines when deployed to production
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// Fix for asset-packagist
Yii::setAlias('@bower', dirname(__DIR__) . '/vendor/bower-asset');
Yii::setAlias('@npm', dirname(__DIR__) . '/vendor/npm-asset');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
