<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RuangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tempat Tidur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tempat Tidur Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ruang_cd',
            'kelas_cd',
            'bangsal_cd',
            'ruang_nm',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
