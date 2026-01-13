<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnitMedisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit Medis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Data Unit Medis', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'medunit_cd',
            'medunit_nm',
            'medunit_tp',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
