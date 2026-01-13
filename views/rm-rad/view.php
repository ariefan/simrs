<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RmRad */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rm Rads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-rad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Unduh', Url::to(['rm-rad/unduh','id'=> $model->id]),['class' => 'btn btn-circle red btn-sm']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'rm_id',
            'medicalunit_cd',
            'nama',
            'catatan:ntext',
            'hasil:ntext',
            'dokter',
            'dokter_nama',
        ],
    ]) ?>

</div>
