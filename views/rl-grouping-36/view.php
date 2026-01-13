<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping36 */

$this->title = $model->rl_ref_36_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping36s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping36-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'rl_ref_36_no' => $model->rl_ref_36_no, 'tindakan_cd' => $model->tindakan_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'rl_ref_36_no' => $model->rl_ref_36_no, 'tindakan_cd' => $model->tindakan_cd], [
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
            'rl_ref_36_no',
            'tindakan_cd',
            'jenis',
        ],
    ]) ?>

</div>
