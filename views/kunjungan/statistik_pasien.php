<?php
use miloschuman\highcharts\Highcharts;

$this->title = "Statisik Pasien Rawat Inap $bangsal";
?>
<div class="row">
	
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
					         'categories' => ['Jenis Kelamin']
					      ],
					      'yAxis' => [
					         'title' => ['text' => 'Jumlah Kunjungan']
					      ],
					      'series' => $isi1,
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
					      'title' => ['text' => 'Tahun Ini'],
					      'xAxis' => [
					         'categories' => ['Jenis Kelamin']
					      ],
					      'yAxis' => [
					         'title' => ['text' => 'Jumlah Kunjungan']
					      ],
					      'series' => $isi,
					   ]
					]);
					?>
            </div>
		</div>
	</div>

	
	
</div>


