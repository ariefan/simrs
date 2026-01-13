<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TarifInventoriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif Inventori';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-inventori-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tarif Inventori', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'seq_no',
            'insurance_cd',
            'kelas_cd',
            'item_cd',
            'tarif',
            // 'account_cd',
            // 'modi_id',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
