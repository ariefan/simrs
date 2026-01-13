<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedisItem */

$this->title = $model->medicalunit_cd;
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-item-view">

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->medicalunit_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->medicalunit_cd], [
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
            'medicalunit_cd',
            'medunit_cd',
            'medicalunit_root',
            'medicalunit_nm',
            'root_st',
            'file_format',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
