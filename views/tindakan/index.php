<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TindakanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tindakan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tindakan-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tindakan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tindakan_cd',
            'tindakan_root',
            'klinik_id',
            'nama_tindakan',
            'total_tarif',
            // 'created',
            // 'modified',
            // 'biaya_wajib',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
