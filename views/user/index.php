<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Buat Pengguna Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            ['attribute'=>'klinik_id','value'=>'klinik.klinik_nama'],
            [
                'attribute' => 'role',
                'value' => 'peran.name'
            ],
            [
                'attribute' => 'bangsal_cd',
                'value' => 'bangsal.bangsal_nm'
            ],
            [
                'attribute' => 'medunit_cd',
                'value' => 'unit.medunit_nm'
            ],
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
