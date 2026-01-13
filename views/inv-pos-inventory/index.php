<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvPosInventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pos Inventori';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-inventory-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Pos Inventori', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pos_cd',
            'pos_nm',
            'description:ntext',
            ['attribute'=>'unit_medis','value'=>function($data){
                $nm='';
                if(!empty($data->bangsal)){
                    $nm=$data->bangsal->bangsal_nm;
                }elseif(!empty($data->unitMedis)){
                    $nm=$data->unitMedis->medunit_nm;
                }
                return $nm;
            }],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
