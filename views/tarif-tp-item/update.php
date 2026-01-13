<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifTpItem */

$this->title = 'Update Tarif Tp Item: ' . $model->seq_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tp Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->seq_no, 'url' => ['view', 'id' => $model->seq_no]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-tp-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
