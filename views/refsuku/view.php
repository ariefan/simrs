<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Refsuku */

$this->title = $model->suku_id;
$this->params['breadcrumbs'][] = ['label' => 'Refsukus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refsuku-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->suku_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->suku_id], [
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
            'suku_id',
            'suku_nama',
        ],
    ]) ?>

</div>
