<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Pasien;
use app\models\Tindakan;
use app\models\RekamMedis;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
$pasien = new Pasien();
$rm_model = RekamMedis::find()->where(['kunjungan_id'=>$kunjungan['kunjungan_id']])->limit(1)->orderBy(['created'=>SORT_DESC])->one();
/* @var $this yii\web\View */
/* @var $model app\models\Bayar */
/* @var $form yii\widgets\ActiveForm */
$subtotal = 0;
$url_tindakan = Url::to(['bayar/get-tindakan']);
?>

<?php
    $tindakan = $model->getBayarTindakan($kunjungan->kunjungan_id,-1);
    $obat = $model->getBayarObat($kunjungan->kunjungan_id,-1); //
    $obat_racik = $model->getBayarObatRacik($kunjungan->kunjungan_id,-1);//
    $radiologi = $model->getBayarRadiologi($kunjungan->kunjungan_id,-1);//
    $lab = $model->getBayarLab($kunjungan->kunjungan_id,-1);//
    // echo '<pre>';print_r($lab);exit;
    $paket = $model->getBayarPaket($kunjungan->kunjungan_id,-1);//
    // echo '<pre>'; print_r($paket); die;
    $ruang = $model->getBayarRuang($kunjungan->kunjungan_id,-1);//
    // exit;
?>

<style type="text/css">
    .tb_items tr:hover {
        background: #eee;
        cursor: pointer;
    }
</style>

<div class="bayar-form">

    <?php $form = ActiveForm::begin(['id'=>'form-bayar-form']); ?>
<div id="canvasList_form">
        <table class="table table-condensed tb_items">
        <thead>
            <th width="5">No.</th>
            <th>Nama Transaksi</th>
            <th>Jumlah</th>
            <th>Tot. Harga</th>
        </thead>
        <tr>
            <td>1</td>
            <td><strong>Umum</strong></td>
            <td></td>
        </tr>

            <?php $subtotal = 0; foreach($paket as $val): $subtotal += $val['tarif']; ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', $val['tarif'],['class'=>'ttl']); ?>
                        
                        <?= Html::checkbox("Bayar[items][umum][{$val['tarif_general_id']}]", true, ['label' => '','value'=>'on','class'=>'checkboxx','checked'=>'checked']) ?>

                        
                            
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_paket'] ?></td>
                    <td>1</td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td>2</td>
            <td><strong>Laboratorium</strong></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($lab as $val): $subtotal += ($val['tarif']*$val['jumlah']); ?>
                <tr>    
                    <td>
                        <?= Html::hiddenInput('ttl', $val['tarif'],['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[lab][{$val['medicalunit_cd']}]")->checkbox(
                        ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_lab'] ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td>3</td>
            <td><strong>Radiologi</strong></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($radiologi as $val): $subtotal += ($val['tarif']*$val['jumlah']); ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', $val['tarif'],['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[rad][{$val['medicalunit_id']}]")->checkbox(
                        ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_radio'] ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td>4</td>
            <td><strong>Tindakan</strong></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($tindakan as $val): $subtotal += ($val['tarif']*$val['jumlah']); ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', $val['tarif']*$val['jumlah'],['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[tindakan][{$val['tarif_id']}]")->checkbox(
                        ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_tindakan'] ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endforeach; ?>

        <tr>
            <td>5</td>
            <td><strong>Obat non Racikan</strong></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($obat as $val): $subtotal += ($val['jumlah']*$val['harga_jual']); ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', $val['harga_jual'],['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[obatNon][{$val['obat_id']}]")->checkbox(
                        ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_merk'] ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['harga_jual']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td>6</td>
            <td><strong>Obat Racikan</strong></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($obat_racik as $val): $subtotal += ($val['jumlah']*$val['harga_jual']); ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', $val['harga_jual'],['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[obat][{$val['obat_id']}]")->checkbox(
                        ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $val['nama_merk'] ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['harga_jual']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <?php if($kunjungan['medunit_cd']==""){ 
            $subtotal += $ruang['tarif']*$ruang['nHari'];?>
            <tr>
                <td>7</td>
                <td><strong>Ruangan</strong></td>
                <td></td>
                <td></td>
            </tr>
            <?php if ($ruang['seq_no']!=''){ ?>
                <tr>
                    <td>
                        <?= Html::hiddenInput('ttl', ($ruang['tarif']*$ruang['nHari']),['class'=>'ttl']); ?>
                        <?= $form->field($model, "items[ruangan][{$ruang['seq_no']}]")->checkbox(
                            ['label'=>'','value'=>'on','class'=>'checkboxx'])->label(false); ?>
                    </td>
                    <td style=" text-indent: 30px;"><?= $ruang['ruang_nm'] ?></td>
                    <td><?= $ruang['nHari'] ?> Hari</td>
                    <td>Rp. <?= number_format($ruang['tarif']*$ruang['nHari'],0,'','.')  ?></td>
                </tr>
            <?php } ?>

        <?php } ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>Rp. <?= number_format($subtotal,0,'','.')  ?></strong></td>
        </tr>
        
    </table>
</div>
    <?php if(!(isset($model->bayar[0]->is_complete) && $model->bayar[0]->is_complete)){ ?>
        <div class="row static-info">
            <div class="col-md-7 name">Total</div>
            <div class="col-md-5 value" id="total-bayar">
                Rp <?= number_format(/*$subtotal*/0,0,'','.') ?>
            </div>
            <?= Html::hiddenInput('subtotal', 0,['class'=>'subtotal']); ?>

        </div>
        <div class="row static-info">
            <div class="col-md-7 name">Bayar</div>
            <div class="col-md-5 value"><?= $form->field($model, 'bayar')->textInput(['maxlength' => true, 'class'=>'jlhBayar_form form-control'])->label(false) ?></div>
        </div>

        <div class="row static-info">
            <div class="col-md-7 name">Kembali</div>
            <div id="kembali" class="col-md-5 value jlhKembali_form"></div>
        </div>
    <?php } ?>

    <?php if(isset($model->is_complete) && $model->is_complete){ ?>
        <div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('PRINT STRUK',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_form();']); ?>
        </div>
    <?php }else{ ?>

        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton('Bayar', ['style'=>'width:100%','class' => 'btn btn-success']) ?>
            </div>
            <div class="col-md-6">
                <?= Html::submitButton('Selesaikan Pembayaran', ['style'=>'width:100%','class' => 'btn btn-primary',
                    'name'=>'selesai',
                    'value'=>'selesai',
                    'data' => [
                        'confirm' => 'Apakah Anda Yakin akan Menyelesaikan Pembayaran ini?',
                        'method' => 'post',
            ],]) ?>
            </div>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function printElem_form()
    {
        w = window.open();
        w.document.write("<?= $this->render('../headerStruk') ?>");
        w.document.write(document.getElementById('canvasHeader').innerHTML);
        w.document.write(document.getElementById('canvasList_form').innerHTML);
        w.document.write("<?= $this->render('../footerStruk',['terbilang'=>$model->getTerbilang($subtotal)]) ?>");
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');
        w.document.write('</body></html>');
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        return true;
    }
</script>

<?php

$this->registerJsFile('@web/plugins/jquery.mask.min.js',['depends'=>'app\assets\MetronicAsset']);
$this->registerJsFile('@web/plugins/numeral.min.js',['depends'=>'app\assets\MetronicAsset']);

$script = <<< JS
    $('.tb_items tr').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    $('.checkboxx').change(function(){
        var total = 0;
        $(".checkboxx").each(function() {
            if($(this).is(':checked')){
               total += parseInt($(this).closest('tr').find('.ttl').val()); 
            }
        });
        $('#total-bayar').html('Rp. '+numeral(total).format('0,0'));
        $('.subtotal').val(total);
    })

    function calculateTotal()
    {
        var semua_total = 0;
        $('.bayar_total').each(function(){
            semua_total += parseInt($(this).html().replace('Rp','').replace('.','').replace(' ',''));
        })
        htm = "Rp "+numeral(semua_total).format('0,0');
        htm2 += '<input type="hidden" name="subtotal" value="'+semua_total+'">';
        $('#total-bayar').html(htm);
        $('#total-bayar').parent().html(htm2);
    }

    $(function(){
        
        $('#form-bayar button:submit').click(function() {
            $('#form-bayar').submit();
            $(this).attr('disabled', true);
        });
        $('.jlhBayar_form').mask('000.000.000.000.000', {reverse: true});
        $('.jlhBayar_form').keyup(function(){
            var total_bayar = parseInt($('#total-bayar').html().replace(/\./g,' ').replace('Rp ',''));
            var bayar = parseInt($('.jlhBayar_form').val().split(".").join(""));
            var kembali = bayar - total_bayar;
            $('.jlhKembali_form').html("Rp "+numeral(kembali).format('0,0'));
        })
    });



JS;
$this->registerJs($script);

?>