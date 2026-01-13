<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifInventori */

$this->title = 'Update Tarif Inventori: ' . $model->seq_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->seq_no, 'url' => ['view', 'id' => $model->seq_no]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-inventori-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
