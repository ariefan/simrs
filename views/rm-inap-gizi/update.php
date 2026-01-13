<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RmInapGizi */

$this->title = 'Update Rm Inap Gizi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rm Inap Gizis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rm-inap-gizi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
