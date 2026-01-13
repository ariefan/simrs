<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedis */

$this->title = $model->medunit_cd;
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->medunit_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->medunit_cd], [
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
            'medunit_cd',
            'medunit_nm',
            'medunit_tp',
        ],
    ]) ?>

</div>
