<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JenisPeriksaLab */

$this->title = 'Update Jenis Periksa Lab: ' . $model->periksa_id;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Periksa Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->periksa_id, 'url' => ['view', 'id' => $model->periksa_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jenis-periksa-lab-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
