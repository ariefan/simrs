<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RefProvinsi */

$this->title = 'Update Ref Provinsi: ' . $model->provinsi_id;
$this->params['breadcrumbs'][] = ['label' => 'Ref Provinsis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->provinsi_id, 'url' => ['view', 'id' => $model->provinsi_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ref-provinsi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
