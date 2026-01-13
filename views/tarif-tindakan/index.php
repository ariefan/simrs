<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TarifTindakanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif Tindakan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tindakan-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tarif Tindakan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tarif_tindakan_id',
            'insurance_cd',
            'kelas_cd',
            'treatment_cd',
            'tarif',
            // 'account_cd',
            // 'modi_id',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
