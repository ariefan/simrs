<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Region;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Region */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="region-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        if($model->isNewRecord ? $status=true : $status=false);
        if($status==true){
            echo $form->field($model, 'region_level')->dropDownList(
                    ['1'=>'provinsi', '2'=>'Kabupaten/Kota', '3'=>'Kecamatan', '4'=>'Kelurahan'],
                    [
                        'prompt'=>'pilih kode level : ', 
                        'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('region/lists?id=').'"+$(this).val(), 
                            function( data ) {$( "select#kode-level" ).html( data );});'            ]
                ); 

            $dataPost=[];
            echo $form->field($model, 'region_root')->dropDownList($dataPost,
                ['prompt'=>'Silahkan Pilih Root: ',
                'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('region/create?id=').'"+$(this).val(), 
                    function( data ) {$( "select#target" ).html( data );});',
                'id'=>'kode-level']);
        }

    ?>

    <?= $form->field($model, 'region_nm')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan Wilayah Baru' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
