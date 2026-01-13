<?php
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;
?>

<!-- if (in_array($kunjungan->medunit_cd, ['POLIIGD','POLIGIGI','POLIBIDAN'])): -->
<?php if($kunjungan->medunit_cd == 'POLIIGD'): ?>
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'rl_32')->widget(Select2::classname(), [
	            'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_32')->queryAll(), 'no', 'jenis_pelayanan'),
	            'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
	            'pluginOptions' => [
	                'allowClear' => true
	            ],
	        ]); ?>
		</div>	
	</div>
<?php endIf; ?>

<?php if($kunjungan->medunit_cd == 'POLIGIGI'): ?>
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'rl_33')->widget(Select2::classname(), [
	            'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_33')->queryAll(), 'no', 'jenis_kegiatan'),
	            'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
	            'pluginOptions' => [
	                'allowClear' => true
	            ],
	        ]); ?>
		</div>	
	</div>
<?php endIf; ?>

<?php if($kunjungan->medunit_cd == 'POLIBIDAN'): ?>
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'rl_34')->widget(Select2::classname(), [
	            'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_34')->queryAll(), 'no', 'jenis_kegiatan'),
	            'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
	            'pluginOptions' => [
	                'allowClear' => true
	            ],
	        ]); ?>
		</div>	
	</div>
<?php endIf; ?>