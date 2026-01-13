<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bangsal */

$this->title = 'Update Bangsal: ' . $model->bangsal_cd;
$this->params['breadcrumbs'][] = ['label' => 'Bangsal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bangsal_nm, 'url' => ['view', 'id' => $model->bangsal_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bangsal-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
