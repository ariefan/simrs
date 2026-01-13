<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RmInap */

$this->title = 'Update Rm Inap: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rm Inaps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rm-inap-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
