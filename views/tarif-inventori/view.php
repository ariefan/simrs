<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TarifInventori */

$this->title = $model->seq_no;
$this->params['breadcrumbs'][] = ['label' => 'Tarif Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-inventori-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->seq_no], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->seq_no], [
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
            'seq_no',
            'insurance_cd',
            'kelas_cd',
            'item_cd',
            'tarif',
            'account_cd',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
