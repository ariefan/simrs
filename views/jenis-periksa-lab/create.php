<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JenisPeriksaLab */

$this->title = 'Create Jenis Periksa Lab';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Periksa Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-periksa-lab-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
