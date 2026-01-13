<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifUnitmedis */

$this->title = 'Update Tarif Unitmedis: ' . $model->tarif_unitmedis_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Unitmedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tarif_unitmedis_id, 'url' => ['view', 'id' => $model->tarif_unitmedis_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-unitmedis-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
