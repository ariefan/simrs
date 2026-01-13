<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\asuransi */

$this->title = $model->insurance_cd;
$this->params['breadcrumbs'][] = ['label' => 'Asuransi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asuransi-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->insurance_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->insurance_cd], [
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
            'insurance_cd',
            'insurance_nm',
        ],
    ]) ?>

</div>