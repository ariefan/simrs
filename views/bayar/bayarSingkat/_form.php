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

<div class="bayar-form">

    <?php $form = ActiveForm::begin(['id'=>'form-bayar-singkat']); ?>
<div id="canvasList_singkat">
        <table class="table table-striped">
        <thead>
            <th width="5">No.</th>
            <th>Nama Transaksi</th>
            <th>Jumlah</th>
        </thead>
            <?php $subtotalPaket = 0; foreach($paket as $val) $subtotalPaket += $val['tarif']; ?>
        <tr>
            <td>1</td>
            <td>Umum</td>
            <td>Rp. <?= number_format($subtotalPaket,0,'','.')  ?><?php $subtotal += $subtotalPaket?></td>
        </tr>
            <?php $subtotalLab = 0; foreach($lab as $val) $subtotalLab += $val['tarif']; ?>
        <tr>
            <td>2</td>
            <td>Laboratorium</td>
            <td>Rp. <?= number_format($subtotalLab,0,'','.')  ?><?php $subtotal += $subtotalLab?></td>
        </tr>
            <?php $subtotalRadio = 0; foreach($radiologi as $val) $subtotalRadio += $val['tarif']; ?>
        <tr>
            <td>3</td>
            <td>Radiologi</td>
            <td>Rp. <?= number_format($subtotalRadio,0,'','.')  ?><?php $subtotal += $subtotalRadio?></td>
        </tr>
            <?php $subtotalTindakan = 0; foreach($tindakan as $val): $subtotalTindakan += $val['tarif']; ?>
        <tr>
            <td>4</td>
            <td>Tindakan</td>
            <td>Rp. <?= number_format($subtotalTindakan*$val['jumlah'],0,'','.')  ?><?php $subtotal += ($subtotalTindakan*$val['jumlah'])?></td>
        </tr>
            <?php endforeach; ?>
            <?php 
                $subtotalObat = 0; foreach($obat as $val) $subtotalObat += ($val['jumlah']*$val['harga_jual']); 
                foreach($obat_racik as $val) $subtotalObat += ($val['jumlah']*$val['harga_jual']);
            ?>
        <tr>
            <td>5</td>
            <td>Obat</td>
            <td>Rp. <?= number_format($subtotalObat,0,'','.')  ?><?php $subtotal += $subtotalObat?></td>
        </tr>

        <?php if($kunjungan['medunit_cd']==""){ ?>
            <tr>
                <td>6</td>
                <td>Ruangan</td>
                <td>Rp. <?= number_format($ruang['tarif']*$ruang['nHari'],0,'','.')  ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>Rp. <?= number_format($subtotal,0,'','.')  ?></strong></td>
        </tr>
        
    </table>
</div>


        <div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('PRINT STRUK',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_singkat();']); ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function printElem_singkat()
    {
        w = window.open();
        w.document.write("<?= $this->render('../headerStruk') ?>");
        w.document.write(document.getElementById('canvasHeader').innerHTML);
        w.document.write(document.getElementById('canvasList_singkat').innerHTML);
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
    function calculateTotal()
    {
        var semua_total = 0;
        $('.bayar_total').each(function(){
            semua_total += parseInt($(this).html().replace('Rp','').replace('.','').replace(' ',''));
        })
        htm = "Rp "+numeral(semua_total).format('0,0');
        htm += '<input type="hidden" name="subtotal" value="'+semua_total+'">';
        $('#total-bayar').html(htm);
    }

    $(function(){
        
        $('#form-bayar button:submit').click(function() {
            $('#form-bayar').submit();
            $(this).attr('disabled', true);
        });
        $('.jlhBayar_singkat').mask('000.000.000.000.000', {reverse: true});
        $('.jlhBayar_singkat').keyup(function(){
            var total_bayar = parseInt($('#total-bayar').html().split(".").join("").replace('Rp ',''));
            var bayar = parseInt($('.jlhBayar_singkat').val().split(".").join(""));
            var kembali = bayar - total_bayar;
            $('.jlhKembali_singkat').html("Rp "+numeral(kembali).format('0,0'));
        })
    });



JS;
$this->registerJs($script);

?>