<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Asuransi;
use app\models\Kelas;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model app\models\TarifTp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tarif_tp')->dropDownList([ 'G' => 'Tarif General', 'P' => 'Tarif Paramedis', 'U'=>'Tarif Unit Medis', 'K'=>'Tarif Kelas', 'T'=>'Tarif Tindakan', 'I'=>'Tarif Inventori (Obat/BHP)'], 
        ['prompt' => 'Pilih Jenis Tarif']) 
    ?>


    <?= $form->field($model, 'tarif_seqno')->widget(Select2::classname(),[
                            'options' => [
                                'placeholder' => 'Pilih Tarif',
                            ],
                            'pluginOptions' => [
                            'maximumInputLength' => 100,
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'ajax' => [
                                'url' => \yii\helpers\Url::toRoute(['tarif-tp/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params){
                                        if ($("#tariftp-tarif_tp").val()!="")
                                            return {q:params.term, jt:$("#tariftp-tarif_tp").val()}; 
                                        else
                                            alert("Pilih Jenis Tarif dulu...");
                                    }')
                            ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                            ],
                        ]); ?>

    <?= $form->field($model, 'insurance_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Asuransi::find()->select(['insurance_cd', 'insurance_nm'])->asArray()->all(), 'insurance_cd', 'insurance_nm'),
        'options' => ['placeholder' => 'insurance_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'kelas_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Kelas::find()->select(['kelas_cd', 'kelas_nm'])->asArray()->all(), 'kelas_cd', 'kelas_nm'),
        'options' => ['placeholder' => 'insurance_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    <button id="tambah-tarif" type="button" class="btn btn-circle green"><i class="fa fa-plus"></i>Tambah Item Tarif</button>

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tarif</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tarif-container">
        <?php foreach ($model->tarifTpItems as $key => $value): ?>
            <tr>
                <td>
                    <select name="TarifTp[tariftp][]" class="form-control">
                        <option <?= $value->tarif_tp=='SARANA'? "selected='selected'":"" ?>value="SARANA">JASA SARANA</option>
                        <option <?= $value->tarif_tp=='SPESIALIS'? "selected='selected'":"" ?>value="SPESIALIS">DOKTER SPESIALIS</option>
                        <option <?= $value->tarif_tp=='PELAKSANA'? "selected='selected'":"" ?>value="PELAKSANA">JASA PELAKSANA</option>
                </td>
                <td><input type="number" class="form-control tarifSatuan" name="TarifTp[tarifSatuan][]" value="<?= $value->tarif_item ?>"></td>
                <td><input type="number" class="form-control jumlah" name="TarifTp[jumlah][]" value="<?= $value->quantity ?>"></td>
                <td><input type="number" class="form-control tarifTotal" name="tariftp-tarifTotal[]" value="<?= ($value->quantity*$value->tarif_item) ?>"></td>
                <td><button type="button" class="btn btn-danger delete-item">x</button></td>

            </tr>
        <?php endForeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$tariftp =Html::dropDownList('s_id', null,['SARANA'=>'JASA SARANA', 'MEDIKAL'=>'JASA MEDIKAL', 'PELAKSANA'=>'JASA PELAKSANA',]);
// echo Select2::widget([
//                             'name' => 'tariftp-tariftp[]',
//                             'class' => 'tariftp',
//                             'data' => ['SARANA'=>'JASA SARANA', 'SPESIALIS'=>'DOKTER SPESIALIS', 'PELAKSANA'=>'JASA PELAKSANA',],
//                             'options' => [
//                                 'placeholder' => 'Pembagian Tarif',
//                             ],
//                         ]); 
$script = <<< JS
    
    $(function(){
        $('#tambah-tarif').click(function(){
            var str = '<tr><td><select name="TarifTp[tariftp][]" class="form-control tariftp"><option value="SARANA">JASA SARANA</option><option value="SPESIALIS">DOKTER SPESIALIS</option><option value="PELAKSANA">JASA PELAKSANA</option></td><td><input type="number" class="form-control tarifSatuan" name="TarifTp[tarifSatuan][]" value="300000"></td><td><input type="number" class="form-control jumlah" name="TarifTp[jumlah][]" value="1"></td><td><input type="number" class="form-control tarifTotal" name="tariftp-tarifTotal[]" value="300000"></td><td><button type="button" class="btn btn-danger delete-item">x</button></td></tr>';
            $('#tarif-container').append(str);
            $('.delete-item').click(function(){
                $(this).parent().parent().remove()
            })

            $('.tarifSatuan').keyup(function(){
                updateRows($(this))
            });

            $('.jumlah').keyup(function(){
                updateRows($(this))
            });

            $('.tarifSatuan').change(function(){
                updateRows($(this))
            });

            $('.jumlah').change(function(){
                updateRows($(this))
            });


        });

        $('.delete-item').click(function(){
            $(this).parent().parent().remove()
        });

        $('.tarifSatuan').keyup(function(){
            updateRows($(this))
        });

        $('.jumlah').keyup(function(){
            updateRows($(this))
        });

        $('.tarifSatuan').change(function(){
            updateRows($(this))
        });

        $('.jumlah').change(function(){
            updateRows($(this))
        });

    });

    function updateRows(oby)
    {
        var tarif = oby.closest('tr').children().find('.tarifSatuan').val();
        var jumlah = oby.closest('tr').children().find('.jumlah').val();

        oby.closest('tr').children().find('.tarifTotal').val(tarif*jumlah);

        
    }
JS;
$this->registerJs($script);

?>