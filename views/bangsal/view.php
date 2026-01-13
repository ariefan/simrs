<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bangsal */

$this->title = $model->bangsal_nm;
$this->params['breadcrumbs'][] = ['label' => 'Bangsal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bangsal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->bangsal_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->bangsal_cd], [
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
            'bangsal_cd',
            'bangsal_nm',
        ],
    ]) ?>

</div>
