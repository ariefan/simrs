<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JenisRujukan */

$this->title = 'Update Jenis Rujukan: ' . $model->referensi_cd;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Rujukans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->referensi_cd, 'url' => ['view', 'id' => $model->referensi_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jenis-rujukan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
