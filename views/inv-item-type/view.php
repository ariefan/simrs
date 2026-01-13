<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemType */

$this->title = $model->type_cd;
$this->params['breadcrumbs'][] = ['label' => 'Tipe Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-type-view">

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->type_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->type_cd], [
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
            'type_cd',
            'type_nm',
        ],
    ]) ?>

</div>
