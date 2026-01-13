<?php



/* @var $this \yii\web\View */

/* @var $content string */



use yii\helpers\Html;

use yii\helpers\Url;

use yii\bootstrap\Nav;

use yii\bootstrap\NavBar;

use yii\widgets\Breadcrumbs;

use app\assets\MetronicAsset;

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">

<head>

    <meta charset="<?= Yii::$app->charset ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>"/>

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

</head>

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">

<?php $this->beginBody() ?>



<div class="page-container">

    

    <!-- BEGIN CONTENT -->

        <!-- BEGIN CONTENT BODY -->

        <div class="page-content">

            

            <div class="row">

                <div class="col-md-12">

                    <div class="portlet light bordered">

                        <div class="portlet-title">

                            <div class="caption font-red-sunglo">

                                <i class="icon-settings font-red-sunglo"></i>

                                <span class="caption-subject bold uppercase"><?= $this->title ?></span>

                            </div>

                        </div>

                        <div class="portlet-body form">

                            <?= $content ?>

                        </div>

                    </div>

                </div>

            

            </div>



        </div>

</div>



<?php 

$this->registerJsFile('@web/metronic/global/scripts/app.min.js',['depends'=>'app\assets\MetronicAsset']); 

$this->registerJsFile('@web/metronic/layouts/layout4/scripts/layout.min.js',['depends'=>'app\assets\MetronicAsset']); 

$this->registerJsFile('@web/metronic/layouts/layout4/scripts/demo.min.js',['depends'=>'app\assets\MetronicAsset']); 

$this->registerJsFile('@web/metronic/layouts/global/scripts/quick-sidebar.min.js',['depends'=>'app\assets\MetronicAsset']); 



$this->endBody() 



?>

</body>

</html>

<?php $this->endPage() ?>

