<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping36 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rl-grouping36-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>
    <table class="table table-hover table-bordered">
    	<thead>
    		<tr>
    			<th></th>
    			<th>Tindakan</th>
    			<th>Jenis</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php foreach($tindakan as $value): ?>
    		<?php 
    		$selected_khusus = '';
    		$selected_besar = '';
    		$selected_sedang = '';
    		$selected_kecil = '';
    		if(isset($jenis_exist[$value['id']])){
    			$selected_khusus = $jenis_exist[$value['id']]=='Khusus' ? 'selected="selected"' : '';
    			$selected_besar = $jenis_exist[$value['id']]=='Besar' ? 'selected="selected"' : '';
    			$selected_sedang = $jenis_exist[$value['id']]=='Sedang' ? 'selected="selected"' : '';
    			$selected_kecil = $jenis_exist[$value['id']]=='Kecil' ? 'selected="selected"' : '';
    		}

    		?>
    		<tr>
    			<td><input type="checkbox" <?= isset($tindakan_exist[$value['id']]) ? 'checked="checked"' : '' ?> name="tindakan[<?= $value['id'] ?>]" value="1"></td>
    			<td width="80%"><?= $value['text'] ?></td>
    			<td>
    				<select class="form-control" name="jenis[<?= $value['id'] ?>]">
    					<option <?= $selected_khusus ?> value="Khusus">Khusus</option>
    					<option <?= $selected_besar ?> value="Besar">Besar</option>
    					<option <?= $selected_sedang ?> value="Sedang">Sedang</option>
    					<option <?= $selected_kecil ?> value="Kecil">Kecil</option>
    				</select>
    			</td>
    		</tr>
    		<?php endforeach; ?>
    	</tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
