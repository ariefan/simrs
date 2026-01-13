<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RuangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Integrasi Siranap';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        <?= Html::a('Perbaharui Sekarang', ['siranap'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Anda Yakin ingin Memperbaharui Sekarang?',
                    'method' => 'post',
                ],
            ])
            ?>

    </p>
    
    <p>
        Terakhir Integrasi: <?= date('d-m-Y H:i:s') ?>
    </p>
</div>
