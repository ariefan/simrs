<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GiziDiet */

$this->title = 'Update Diet: ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Gizi Diets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gizi-diet-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
