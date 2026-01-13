<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RmLabNapza */

$this->title = 'Update Rm Lab Napza: ' . $model->lab_napza_id;
$this->params['breadcrumbs'][] = ['label' => 'Rm Lab Napzas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lab_napza_id, 'url' => ['view', 'id' => $model->lab_napza_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rm-lab-napza-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
