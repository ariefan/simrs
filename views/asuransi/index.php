<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AsuransiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asuransi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asuransi-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Asuransi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'insurance_cd',
            'insurance_nm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>