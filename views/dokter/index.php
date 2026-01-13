<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DokterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dokter';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dokter-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="alert alert-success">
        Dokter dibuat melalui akun user, setelah itu dapat diedit di menu ini
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'user_id',
            'nama',
            //'no_telp',
            //'no_telp_2',
            'spesialis',
            'waktu_praktek',
            
            // 'foto:ntext',
            // 'alamat:ntext',
            // 'tanggal_lahir',
            // 'created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
