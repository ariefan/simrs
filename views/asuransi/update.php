<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\asuransi */

$this->title = 'Update Asuransi: ' . $model->insurance_cd;
$this->params['breadcrumbs'][] = ['label' => 'Asuransi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->insurance_cd, 'url' => ['view', 'id' => $model->insurance_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asuransi-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
