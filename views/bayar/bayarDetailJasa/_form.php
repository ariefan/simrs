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

    <?php $form = ActiveForm::begin(['id'=>'form-bayar-detail-jasa']); ?>
<div id="canvasList_detailJasa">
        <table class="table table-striped">
        <thead>
            <th>Nama Transaksi</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>JS</th>
            <th>JM</th>
            <th>JP</th>
            <th>Total (Rp. ,-)</th>
        </thead>

        <tr>
            <td><strong>Umum</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

            <?php $subtotal = 0; foreach($paket as $val): $subtotal += $val['tarif'];
                $details = $model->getDetails($val['tarif_id'], 'G', $kunjungan['insurance_cd'], NULL ); ?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_paket'] ?></td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                    <td>1</td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity ?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SARANA']->quantity ?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['SARANA']->quantity ?></td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td><strong>Laboratorium</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($lab as $val): $subtotal += ($val['tarif']*$val['jumlah']); 
                $details = $model->getDetails($val['tarif_id'], 'U', $kunjungan['insurance_cd'], NULL ); ?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_lab'] ?></td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td><strong>Radiologi</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($radiologi as $val): $subtotal += ($val['tarif']*$val['jumlah']); 
                $details = $model->getDetails($val['tarif_id'], 'U', $kunjungan['insurance_cd'], NULL ); ?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_radio'] ?></td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endforeach; ?>

        <tr>
            <td><strong>Tindakan</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

            <?php foreach($tindakan as $val): $subtotal += ($val['tarif']*$val['jumlah']);
                $details = $model->getDetails($val['tindakan_id'], NULL, $kunjungan['insurance_cd'], NULL ); ?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_tindakan'] ?></td>
                    <td>Rp. <?= number_format($val['tarif'],0,'','.')  ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SPESIALIS']->quantity *$val['jumlah'] ?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['PELAKSANA']->quantity *$val['jumlah'] ?></td>
                    <td>Rp. <?= number_format($val['tarif']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>

        <tr>
            <td><strong>Obat non Racikan</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
            <?php foreach($obat as $val): $subtotal += ($val['jumlah']*$val['harga_jual']);
                $details = $model->getDetails($val['tarif_id'], 'I', $kunjungan['insurance_cd'], NULL );?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_merk'] ?></td>
                    <td>Rp. <?= number_format($val['harga_jual'],0,'','.')  ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td>Rp. <?= number_format($val['harga_jual']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>
        <tr>
            <td><strong>Obat Racikan</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
            <?php foreach($obat_racik as $val): $subtotal += ($val['jumlah']*$val['harga_jual']); 
                $details = $model->getDetails($val['tarif_id'], 'I', $kunjungan['insurance_cd'], NULL );?>
                <tr>
                    <td style=" text-indent: 30px;"><?= $val['nama_merk'] ?></td>
                    <td>Rp. <?= number_format($val['harga_jual'],0,'','.')  ?></td>
                    <td><?= $val['jumlah'] ?></td>
                    <td><?= @$details['SARANA']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td><?= @$details['SPESIALIS']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td><?= @$details['PELAKSANA']->tarif_item* @$details['SARANA']->quantity * $val['jumlah']?></td>
                    <td>Rp. <?= number_format($val['harga_jual']*$val['jumlah'],0,'','.')  ?></td>
                </tr>
            <?php endForeach; ?>   

        <?php if($kunjungan['tipe_kunjungan']=="Rawat Inap"){ 
            $subtotal += $ruang['tarif']*$ruang['nHari'];
            $ruangDetails = $model->getDetails($ruang['seq_no'], 'K', $kunjungan['insurance_cd'], NULL );?>
                <tr>
                    <td><strong>Ruangan</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                    <tr>
                        <td style=" text-indent: 30px;"><?= $ruang['ruang_nm'] ?></td>
                        <td>Rp. <?= number_format($ruang['tarif'],0,'','.')  ?></td>
                        <td><?= $ruang['nHari'] ?> Hari</td>
                        <td><?= @$ruangDetails['SARANA']->tarif_item* @$ruangDetails['SARANA']->quantity * $ruang['nHari']?></td>
                        <td><?= @$ruangDetails['SPESIALIS']->tarif_item* @$ruangDetails['SARANA']->quantity * $ruang['nHari']?></td>
                        <td><?= @$ruangDetails['PELAKSANA']->tarif_item* @$ruangDetails['SARANA']->quantity * $ruang['nHari']?></td>
                        <td>Rp. <?= number_format($ruang['tarif']*$ruang['nHari'],0,'','.')  ?></td>
                    </tr>
        <?php } ?>    
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Rp. <?= number_format($subtotal,0,'','.')  ?></strong></td>
        </tr> 
    </table>
</div>

        <div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('PRINT STRUK',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_detailJasa();']); ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function printElem_detailJasa()
    {
        w = window.open();
        w.document.write("<?= $this->render('../headerStruk') ?>");
        w.document.write(document.getElementById('canvasHeader').innerHTML);
        w.document.write(document.getElementById('canvasList_detailJasa').innerHTML);
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
        $('.jlhBayar_detailJasa').mask('000.000.000.000.000', {reverse: true});
        $('.jlhBayar_detailJasa').keyup(function(){
            var total_bayar = parseInt($('#total-bayar').html().split(".").join("").replace('Rp ',''));
            var bayar = parseInt($('.jlhBayar_detailJasa').val().split(".").join(""));
            var kembali = bayar - total_bayar;
            $('.jlhKembali_detailJasa').html("Rp "+numeral(kembali).format('0,0'));
        })
    });



JS;
$this->registerJs($script);

?>