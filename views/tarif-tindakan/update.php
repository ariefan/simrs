<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifTindakan */

$this->title = 'Update Tarif Tindakan: ' . $model->tarif_tindakan_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tindakan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tarif_tindakan_id, 'url' => ['view', 'id' => $model->tarif_tindakan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-tindakan-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
