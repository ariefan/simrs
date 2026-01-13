<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TarifParamedis */

$this->title = $model->tarif_paramedis_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Paramedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-paramedis-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tarif_paramedis_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tarif_paramedis_id], [
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
            'tarif_paramedis_id',
            'insurance_cd',
            'kelas_cd',
            'specialis_cd',
            'paramedis_tp',
            'tarif',
            'account_cd',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
