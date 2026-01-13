<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifGeneral */

$this->title = 'Update Tarif General: ' . $model->tarif_general_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif General', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tarif_general_id, 'url' => ['view', 'id' => $model->tarif_general_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-general-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
