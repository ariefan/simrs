<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\InvItemMaster;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\InvItemMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Surveilans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" name="start_date" placeholder="Tanggal"> s/d
        <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" name="end_date" placeholder="Tanggal">
    </div>
    

    <div class="form-group">
        <label>Tipe Kunjungan</label>
        <?=
        Select2::widget([
            'name' => 'tipe_kunjungan',
            'value' => '',
            'data' => ['Rawat Jalan'=>'Rawat Jalan','Rawat Inap'=>'Rawat Inap'],
            'options' => ['placeholder' => 'Pilih Tipe Kunjungan ...']
        ]);
        ?>
    </div>

    <div class="form-group">
        <label>Tipe Diagnosis</label>
        <?=
        Select2::widget([
            'name' => 'jenis_diagnosa',
            'value' => '',
            'data' => ['primer'=>'Primer','sekunder'=>'Sekunder','gabungan'=>'Gabungan'],
            'options' => ['placeholder' => 'Pilih Tipe Diagnosis ...']
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
