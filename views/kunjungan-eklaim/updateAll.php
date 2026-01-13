<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */

$this->title = 'Update Kunjungan Eklaim: ' . $model->kunjungan_id;
$this->params['breadcrumbs'][] = ['label' => 'Kunjungan Eklaims', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kunjungan_id, 'url' => ['view', 'id' => $model->kunjungan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kunjungan-eklaim-update">

    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div>
