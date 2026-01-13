<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping38 */

$this->title = $model->rl_ref_38_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping38s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping38-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'rl_ref_38_no' => $model->rl_ref_38_no, 'medicalunit_cd' => $model->medicalunit_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'rl_ref_38_no' => $model->rl_ref_38_no, 'medicalunit_cd' => $model->medicalunit_cd], [
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
            'rl_ref_38_no',
            'medicalunit_cd',
        ],
    ]) ?>

</div>
