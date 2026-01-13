<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<!-- <center><h3><?= $klinik->klinik_nama ?></h3></center>
<center><p><?= $klinik->alamat ?></p></center> -->
<div id="canvasList_detail">
<table class="table table-hover">
    <tr>
        <th>Tgl</th>
        <td><?= $model->created ?></td>
    </tr>
    <tr>
        <th>Dr.</th>
        <td><?= $dokter->nama ?></td>
    </tr>
     <tr>
            <th>Pro</th>
            <td>: <?= $pasien->nama ?></td>
        </tr>
        <tr>
            <th>Umur</th>
            <td>: <?= !(empty($pasien->tanggal_lahir)) ? $model->getAge($pasien->tanggal_lahir) : 0 ?> Tahun</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>: <?= $pasien->alamat ?></td>
        </tr>
        <tr>
            <th>No. RM</th>
            <td>: <?= $pasien->mr ?></td>
        </tr>
</table>

<div class="row">
    <div class="col-md-12">
        <h4>Obat</h4>
        <table class="table table-striped table-hover">
            <thead>
                <td>Nama obat</td>
                <td>Banyak</td>
                <td>Pemakaian</td>
           
            </thead>
            <tbody>
                <tbody>
                    <?php foreach($rm_obat as $value): ?>
                        <tr>
                            <td><?= $value['nama_obat'] ?> <?= empty($value['obat_id']) ? ' (Resep)' : '' ?></td>
                            <td><?= $value['jumlah'].' '.$value['satuan'] ?></td>
                            <td><?= $value['signa'] ?></td>
                           
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </tbody>
        </table>
        <?php foreach($rm_obatracik as $key=>$value): ?>
        <h4>Obat Racik #<?= $key+1 ?></h4>
        <table class="table table-striped table-hover">
            
            <tbody>
                <tbody>
                    <?php foreach($rm_obatracik_komponen[$value['racik_id']] as $val): ?>
                        <tr>
                            <td><?= $val['nama_obat'] ?> <?= empty($val['obat_id']) ? ' (Resep)' : '' ?></td>
                            <td><?= $val['jumlah'].' '.$val['satuan'] ?></td>
                            <td><?= $val['proses_stok'] == 0 ? 'Belum Diproses' : 'Terproses' ?></td>

                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2">M.F.Pulv : <?= $value['jumlah'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Signa : <?= $value['signa'] ?></td>
                    </tr>
                </tbody>
            </tbody>
        </table>
        <?php endforeach; ?>
    </div>
</div>
</div>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <?php $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] )); ?>
    <?= Html::submitButton('Proses Stok', ['class' => 'btn btn-success','data' => [
            'confirm' => 'Apakah anda yakin akan melakukan Proses Stok?',
            'method' => 'post',
          ],]) ?>
    <?= Html::a('Edit', ['check-obat','id'=>$id,'asal'=>'kunjungan/farmasi'], ['class' => 'btn btn-danger']) ?>
    <?= Html::a('Batalkan Proses Stok', ['cancel-proses-stok','id'=>$id], ['class' => 'btn btn-default','data' => [
            'confirm' => 'Apakah anda yakin akan melakukan Pembatalan Stok?',
            'method' => 'post',
          ],]) ?>

    <div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('cetak resep',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_print();']); ?>
        </div>
   

</div>
<h4>Status Stock</h4>
<table class="table table-striped table-hover">
            <thead>
                <td>Nama obat</td>
                <td>Proses</td>
           
            </thead>
            <tbody>
                <tbody>
                    <?php foreach($rm_obat as $value): ?>
                        <tr>
                            <td><?= $value['nama_obat'] ?> <?= empty($value['obat_id']) ? ' (Resep)' : '' ?></td>
                            <td><?= $value['proses_stok'] == 0 ? 'Belum Diproses' : 'Terproses' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </tbody>
        </table>
<?php ActiveForm::end(); ?>



<script type="text/javascript">
    function printElem_print()
    {
        
        w = window.open();
        w.document.write("<?= $this->render('headerStruk') ?>");

        w.document.write(document.getElementById('canvasList_detail').innerHTML);
            
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');
        w.document.write('</body></html>');
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        return false;
    }

    <?php if($cetak): ?>
    window.onload = printElem_print();
    window.location = "<?= Url::to(['kunjungan/farmasi']) ?>"
    <?php endif; ?>
    
</script>





        