<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifParamedis */

$this->title = 'Update Tarif Paramedis: ' . $model->tarif_paramedis_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Paramedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tarif_paramedis_id, 'url' => ['view', 'id' => $model->tarif_paramedis_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-paramedis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
