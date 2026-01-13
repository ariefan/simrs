<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\RmInapSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rm Inaps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-inap-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rm Inap', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rm_id',
            'anamnesis:ntext',
            'pemeriksaan_fisik:ntext',
            'assesment:ntext',
            // 'plan:ntext',
            // 'created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
