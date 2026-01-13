<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JenisRujukan */

$this->title = $model->referensi_cd;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Rujukans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-rujukan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->referensi_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->referensi_cd], [
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
            'referensi_cd',
            'referensi_nm',
            'reff_tp',
            'referensi_root',
            'dr_nm',
            'address:ntext',
            'phone',
            'modi_datetime',
            'modi_id',
            'info_01',
            'info_02',
        ],
    ]) ?>

</div>
