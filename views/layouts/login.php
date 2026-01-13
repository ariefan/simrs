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

<body class=" login">

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