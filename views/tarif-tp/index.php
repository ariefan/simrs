<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TarifTpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif Tp';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tp-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tarif Tp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tariftp_no',
            'tariftp_nm:ntext',
            'insurance_cd',
            'kelas_cd',
            'tarif_total',
            // 'trx_tarif_seqno',
            // 'modi_id',
            // 'tarif_tp',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
