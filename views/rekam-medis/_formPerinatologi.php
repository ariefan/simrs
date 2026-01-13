<?php
	use kartik\select2\Select2;
	use yii\helpers\ArrayHelper;
?>

<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'rl_35_1')->textInput(['type'=>'number']) ?>
	</div>	
	<div class="col-md-6">
		<?= $form->field($model, 'rl_35_2')->dropDownList(ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_35 where no like "2.%"')->queryAll(), 'no', 'jenis_kegiatan'),['prompt'=>"Kosongkan apabila bayi hidup."]); ?>
	</div>	
	 <!-- style="display: none;" -->
	<div class="col-md-6 sebapMati" style="display: none;">
		<?= $form->field($model, 'rl_35_3')->widget(Select2::classname(), [
            'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_35 where no like "3.%"')->queryAll(), 'no', 'jenis_kegiatan'),
            'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
	</div>	
</div>

<?php $this->registerJs("
	$('#rekammedis-rl_35_2').change(function(){
		if($('#rekammedis-rl_35_2').val()!=''){
			$('.sebapMati').show();
		}
		else{
			$('.sebapMati').hide();
			$('#rekammedis-rl_35_3').select2('val', '');
		}
	});
") ?>