<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TarifTp */

$this->title = 'Update Tarif Tp: ' . $model->tariftp_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tariftp_no, 'url' => ['view', 'id' => $model->tariftp_no]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarif-tp-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
