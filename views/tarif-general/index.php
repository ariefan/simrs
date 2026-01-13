<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TarifGeneralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif General';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-general-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tarif General', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tarif_general_id',
            'tarif_nm',
            'insurance_cd',
            'kelas_cd',
            'tarif',
            // 'auto_add',
            // 'medical_tp',
            // 'account_cd',
            // 'modi_id',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
