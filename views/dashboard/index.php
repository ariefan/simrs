<?php
use miloschuman\highcharts\Highcharts;

$this->title = "Dashboard";
?>
<div class="row">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Kunjungan</span>
                    <span class="caption-helper">Per Bulan</span>
                </div>
            </div>
            <div class="portlet-body">
            	<?php
					echo Highcharts::widget([
					   'options' => [
					   	  'chart' => ['type'=>'column'],
					      'title' => ['text' => 'Tahun Ini'],
					      'xAxis' => [
					         'categories' => $bulan
					      ],
					      'yAxis' => [
					         'title' => ['text' => 'Jumlah Kunjungan']
					      ],
					      'series' => [
					         ['name' => 'Bulan', 'data' => $kunjungan_bulan],
					      ]
					   ]
					]);
					?>
            </div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12 col-sm-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Kunjungan</span>
                    <span class="caption-helper">Berdasar Jenis Kelamin</span>
                </div>
            </div>
            <div class="portlet-body">
            	<?php
					echo Highcharts::widget([
					   'options' => [
					   	  'chart' => ['type'=>'column'],
					      'title' => ['text' => 'Hari Ini'],
					      'xAxis' => [
					         'categories' => ['Laki-Laki','Perempuan']
					      ],
					      'yAxis' => [
					         'title' => ['text' => 'Jumlah Kunjungan']
					      ],
					      'series' => [
					         ['name' => 'Jenis Kelamin', 'data' => $hari_ini],
					      ]
					   ]
					]);
					?>
            </div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12 col-sm-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Kunjungan</span>
                    <span class="caption-helper">Berdasar Jenis Kelamin</span>
                </div>
            </div>
            <div class="portlet-body">
            	<?php
					echo Highcharts::widget([
					   'options' => [
					   	  'chart' => ['type'=>'column'],
					      'title' => ['text' => 'Bulan Ini'],
					      'xAxis' => [
					         'categories' => ['Laki-Laki','Perempuan']
					      ],
					      'yAxis' => [
					         'title' => ['text' => 'Jumlah Kunjungan']
					      ],
					      'series' => [
					         ['name' => 'Jenis Kelamin', 'data' => $bulan_ini],
					      ]
					   ]
					]);
					?>
            </div>
		</div>
	</div>

	<div class="col-lg-6 col-xs-12 col-sm-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">TOP 10 Diagnosis</span>
                    <span class="caption-helper">Bulan Ini</span>
                </div>
            </div>
            <div class="portlet-body">
            	<?php
					echo Highcharts::widget([
					   'options' => [
					   	  'chart' => ['type'=>'pie'],
					      'title' => ['text' => 'Bulan Ini'],
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
					         'data' => $diagnosis_bulan_ini
					         ],
					      ]
					   ]
					]);
					?>
            </div>
		</div>
	</div>

	<div class="col-lg-6 col-xs-12 col-sm-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">TOP 10 Diagnosis</span>
                    <span class="caption-helper">Tahun Ini</span>
                </div>
            </div>
            <div class="portlet-body">
            	<?php
					echo Highcharts::widget([
					   'options' => [
					   	  'chart' => ['type'=>'pie'],
					      'title' => ['text' => 'Tahun Ini'],
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
					         'data' => $diagnosis_tahun_ini
					         ],
					      ]
					   ]
					]);
					?>
            </div>
		</div>
	</div>
</div>


