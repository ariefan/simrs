<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifKelas */

$this->title = 'Update Tarif Kelas: ' . $model->seq_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->seq_no, 'url' => ['view', 'id' => $model->seq_no]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-kelas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
