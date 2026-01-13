<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosInventory */

$this->title = $model->pos_cd;
$this->params['breadcrumbs'][] = ['label' => 'Pos Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-inventory-view">

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->pos_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->pos_cd], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            }]
        ],
    ]) ?>

</div>
