<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Tindakan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daftar-tindakan">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div id="modal" class="fade modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Tindakan</h4>
                </div>
                <div class="modal-body">
                    <div id='modalContent'></div>
                </div>

            </div>
        </div>
    </div>
<div class="table-responsive">
    <?= GridView::widget([
   'dataProvider' => $dataProvider,
 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'treatment_cd',
            [
                'attribute'=>'Nama Tindakan',
                'value'=>'treatmentCd.nama_tindakan'
            ],
            'kelas_cd',
            'tarif',
            
            
        ],
    ]); ?>
    </div>
</div>


