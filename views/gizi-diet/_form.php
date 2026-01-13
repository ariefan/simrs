<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\GiziMakanan;

/* @var $this yii\web\View */
/* @var $model app\models\GiziDiet */
/* @var $form yii\widgets\ActiveForm */


$start_counter = isset($counter) ? count($counter) + 1 : 1;
?>

<?php
    Modal::begin([
            'id' => 'modal',
            'size' => 'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

?>

<div class="gizi-diet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_diet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>
    
    <?= Html::button('<i class="fa fa-plus"></i> Bahan Makanan', ['value'=>Url::to(['gizi-diet/tambah-makanan']), 'id' => 'addMakanan', 'class' => 'btn green-haze btn-outline sbold uppercase']) ?>
        <br/><br/>
        <table id="table-makanan" class="table table-hover table-light">
            <thead>
                <th>Bahan Makanan</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>&nbsp;</th>
            </thead>
            <tbody id="addMakanan">
                <?php $makanans = GiziMakanan::find()->where(['kode_diet'=>$model->kode])->all();  ?> 
                <?php foreach($makanans as $makanan): ?>
                    <tr>
                        <td><input type="text" value="<?= $makanan->bahan_makanan ?>" class="form-control" required="" placeholder="Bahan Makanan" name="makanan[<?= $makanan->id ?>][bahan_makanan]"></td>
                       
                        <td><input type="number" value="<?= $makanan->jumlah_gizi ?>" class="form-control" required="" placeholder="Jumlah Gizi" name="makanan[<?= $makanan->id ?>][jumlah_gizi]"></td>
                        <td><input type="text" value="<?= $makanan->satuan ?>" class="form-control" required="" placeholder="Satuan" name="makanan[<?= $makanan->id ?>][satuan]"></td>
                        <td><button type="button" class="btn btn-danger delete-item"><span class='glyphicon glyphicon-remove'></span></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br/><br/>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    $(document).ready(function() {

        $('#addMakanan').click(function(){
            kode_diet = $(this).attr('kode_diet')
            bahan_makanan = $(this).attr('bahan_makanan')
            kandungan = $(this).attr('kandungan')
            str = "<tr>";
            str += "<td><input type='text' class='form-control' required placeholder='Bahan Makanan' name='makanan[bahan_makanan][]'></td>";
           
            str += "<td><input type='text' class='form-control' required placeholder='Jumlah Gizi' name='makanan[jumlah_gizi][]'></td>";
            str += "<td><input type='text' class='form-control' required placeholder='Satuan' name='makanan[satuan][]'></td>";
    
            str += "<td><button type='button' class='btn btn-danger delete-item'><span class='glyphicon glyphicon-remove'></span></button></td>";
            str += "</tr>";

            $('#table-makanan').append(str);

            $('.delete-item').click(function(){
                $(this).parent().parent().remove()
            })
        })

        $('.delete-item').click(function(){
            $(this).parent().parent().remove()
        })

        $('.modalWindow').click(function(){
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })

    });

JS;

$this->registerJs($script);
?>