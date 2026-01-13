<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedis */

$this->title = 'Mengubah Data Unit Medis: ' . $model->medunit_cd;
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->medunit_cd, 'url' => ['view', 'id' => $model->medunit_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unit-medis-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
