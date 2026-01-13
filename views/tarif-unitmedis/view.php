<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TarifUnitmedis */

$this->title = $model->tarif_unitmedis_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Unitmedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-unitmedis-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tarif_unitmedis_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tarif_unitmedis_id], [
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
            'tarif_unitmedis_id',
            'insurance_cd',
            'kelas_cd',
            'medicalunit_cd',
            'tarif',
            'account_cd',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
