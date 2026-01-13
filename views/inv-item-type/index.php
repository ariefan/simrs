<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvItemTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipe Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-type-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah tipe item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type_cd',
            'type_nm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
