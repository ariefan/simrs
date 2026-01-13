<?php

use yii\helpers\Html;

use yii\helpers\Url;

use app\assets\MetronicLoginAsset;



MetronicLoginAsset::register($this);



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

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<?php $this->beginBody() ?>

        <!-- BEGIN LOGO -->

        <div class="logo">

            	<?= Html::img('@web/metronic/pages/img/logo-big-white.png',['style'=>'height:100px']) ?>

        </div>

        <!-- END LOGO -->

        <!-- BEGIN LOGIN -->

        <div class="content">

        <?= $content ?>

        </div>



<?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>