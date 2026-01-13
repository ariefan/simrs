<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;

$this->title = 'Top Penyakit';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="instruksi-index">

    <?php $form = ActiveForm::begin(["id"=>"form-pasien"]); ?>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal Awal</label>
            <input type="date" class="form-control" name="tgl_awal" value="<?= date('Y') ?>-01-01">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal Akhir</label>
            <input type="date" class="form-control" name="tgl_akhir" value="<?= date('Y') ?>-12-31">
        </div>
      </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Lihat', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php if(!empty($d)): ?>

  <?php
    echo Highcharts::widget([
       'options' => [
          'chart' => ['type'=>'pie'],
          'title' => ['text' => 'Top 10 Diagnosis'],
          'tooltip' => ['pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'],
          'plotOptions'=> [
                  'pie' => [
                      'allowPointSelect' => true,
                      'cursor'=> 'pointer',
                      'dataLabels' => [
                          'enabled'=> false
                      ],
                      'showInLegend'=> true
                  ]
              ],
          
          'series' => [
             [
             'name' => 'Diagnosis', 
             'colorByPoint' => true,
             'data' => array_slice($d,0,10)
             ],
          ]
       ]
    ]);
    ?>


<table class="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Diagnosis / Penyakit</th>
      <th>Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($d as $key => $value): ?>
    <tr>
       <td><?= $key+1 ?></td>
       <td><?= $value['full_name'] ?></td>      
       <td><?= $value['y'] ?></td>
    </tr>
    <?php endforeach ?>

  </tbody>
</table>


<?php endif; ?>
