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

    <?php $form = ActiveForm::begin(['id'=>'form-bayar-detail']); ?>
<div id="canvasList_detail">
        <table class="table table-striped">
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
            <td></td>
        </tr>

            <?php $subtotal = 0; foreach($paket as $val): $subtotal += $val['tarif']; ?>
                <tr>
                    <td></td>
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
                    <td></td>
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
                    <td></td>
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
                    <td></td>
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
                    <td></td>
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
                    <td></td>
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

            <tr>
                <td></td>
                <td style=" text-indent: 30px;"><?= $ruang['ruang_nm'] ?></td>
                <td><?= $ruang['nHari'] ?> Hari</td>
                <td>Rp. <?= number_format($ruang['tarif']*$ruang['nHari'],0,'','.')  ?></td>
            </tr>

        <?php } ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>Rp. <?= number_format($subtotal,0,'','.')  ?></strong></td>
        </tr>
        
    </table>
</div>

        <div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('PRINT STRUK',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_detail();']); ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function printElem_detail()
    {
        w = window.open();
        w.document.write("<?= $this->render('../headerStruk') ?>");
        w.document.write(document.getElementById('canvasHeader').innerHTML);
        w.document.write(document.getElementById('canvasList_detail').innerHTML);
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
        $('.jlhBayar_detail').mask('000.000.000.000.000', {reverse: true});
        $('.jlhBayar_detail').keyup(function(){
            var total_bayar = parseInt($('#total-bayar').html().split(".").join("").replace('Rp ',''));
            var bayar = parseInt($('.jlhBayar_detail').val().split(".").join(""));
            var kembali = bayar - total_bayar;
            $('.jlhKembali_detail').html("Rp "+numeral(kembali).format('0,0'));
        })
    });



JS;
$this->registerJs($script);

?>