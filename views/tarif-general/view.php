<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TarifGeneral */

$this->title = $model->tarif_general_id;
$this->params['breadcrumbs'][] = ['label' => 'Tarif General', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-general-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tarif_general_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tarif_general_id], [
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
            'tarif_general_id',
            'tarif_nm',
            'insurance_cd',
            'kelas_cd',
            'tarif',
            'auto_add',
            'medical_tp',
            'account_cd',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
