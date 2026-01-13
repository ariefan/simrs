<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TarifTp */

$this->title = $model->tariftp_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tp-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tariftp_no], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tariftp_no], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tariftp_no',
            'tariftp_nm:ntext',
            'insurance_cd',
            'kelas_cd',
            'tarif_total',
            'trx_tarif_seqno',
            'modi_id',
            'tarif_tp',
            'modi_datetime',
        ],
    ]) ?>

</div>
<table class="table table-hover">
    <thead>
        <th>#</th>
        <th>Tipe Tarif</th>
        <th>Tarif</th>
        <th>Jumlah</th>
        <th>Total Tarif</th>
    </thead>
    <tbody>
        <?php $i=1; foreach ($model->tarifTpItems as $key => $value): ?>
            <tr>
            <td><?= $i++ ?></td>
            <td><?= $value->tarif_tp ?></td>
            <td><?= $value->tarif_item ?></td>
            <td><?= $value->quantity ?></td>
            <td><?= ($value->quantity*$value->tarif_item) ?></td>
            </tr>
        <?php endForeach; ?>
    </tbody>
</table>