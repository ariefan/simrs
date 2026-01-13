<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnitMedisItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Item Unit Medis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Membuat Data Item Unit Medis Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'medicalunit_cd',
            'medunit_cd',
            'medicalunit_root',
            'medicalunit_nm',
            'root_st',
            // 'file_format',
            // 'modi_id',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
