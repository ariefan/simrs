<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AccountGroup */

$this->title = $model->accgroup_cd;
$this->params['breadcrumbs'][] = ['label' => 'Account Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->accgroup_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->accgroup_cd], [
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
            'accgroup_cd',
            'accgroup_nm',
            'order_no',
        ],
    ]) ?>

</div>
