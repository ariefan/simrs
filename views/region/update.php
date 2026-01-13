<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Region */

$this->title = 'Mengubah Data Wilayah: ' . $model->region_cd;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->region_cd, 'url' => ['view', 'id' => $model->region_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="region-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
