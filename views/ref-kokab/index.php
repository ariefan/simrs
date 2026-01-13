<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RefKokabSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ref Kokabs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-kokab-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ref Kokab', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'kota_id',
            'kokab_nama',
            'provinsi_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
