<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedisItem */

$this->title = 'Mengubah Data Item Unit Medis: ' . $model->medicalunit_cd;
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->medicalunit_cd, 'url' => ['view', 'id' => $model->medicalunit_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unit-medis-item-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
