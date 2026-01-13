<?php
    use yii\helpers\Html; 
    $hari = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
?>
<table width="100%">
    <tr>
        <td rowspan="3"><?= Html::img('@web/metronic/layouts/layout4/img/logo-light.png',['style'=>'width:60px']) ?></td>
        <td style="text-align: center; font-weight: bolder">
            PEMERINTAH KABUPATEN BERAU<br/>
            RSUD Dr. ABDUL RIVAI
        </td>
        <td rowspan="3"><?= Html::img('@web/img/left-header.jpg',['style'=>'width:60px']) ?></td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bolder">INSTALASI LABORATORIUM KLINIK</td>
    </tr>
    <tr>
        <td style="text-align: center">Jalan Pulau Panjang Telp. (0554) - 21098 Tanjung Redeb</td>
    </tr>
</table>
<hr/>

<p style="text-align: center; font-weight: bolder;">
    <u style="font-size: larger;">SURAT KETERANGAN</u><br/>
    Nomor : <?= $model->nomor_surat ?>
</p>

<p style="padding: 20px">
    Yang bertanda tangan dibawah ini menerangkan bahwa : 
    <table style="font-weight: bolder; margin-top: 10px">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><?= $model->rm->mr0->nama ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td><?= $model->rm->mr0->jk ?></td>
        </tr>
        <tr>
            <td>Tempat/ Tanggal Lahir</td>
            <td>:</td>
            <td><?= date('d-m-Y', strtotime($model->rm->mr0->tanggal_lahir)) ?></td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td><?= $model->rm->mr0->agama ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td><?= $model->rm->mr0->pekerjaan ?></td>
        </tr>
        <tr>
            <td>Kewarganegaraan</td>
            <td>:</td>
            <td><?= $model->rm->mr0->warga_negara ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $model->rm->mr0->alamat ?></td>
        </tr>
    </table>
    <table style="font-weight: bolder; margin-top: 20px; margin-bottom: 20px">
        <tr>
            <td>Nomor Surat</td>
            <td>:</td>
            <td><?= $model->nomor_surat ?></td>
        </tr>
        <tr>
            <td>Tanggal Surat</td>
            <td>:</td>
            <td><?= date('d F Y', strtotime($model->tanggal_surat)) ?></td>
        </tr>
        <tr>
            <td>Permintaan</td>
            <td>:</td>
            <td><?= $model->permintaan ?></td>
        </tr>
    </table>
    Telah melakukan tes narkoba <i>urine</i> yang bersangkutan pada : 
    <table style="font-weight: bolder; margin-top: 20px; margin-bottom: 20px">
        <tr>
            <td>Hari</td>
            <td>:</td>
            <td><?= $hari[date('N', strtotime($model->tanggal_surat))] ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= date('d F Y', strtotime($model->tanggal_surat)) ?></td>
        </tr>
        <tr>
            <td>Jam</td>
            <td>:</td>
            <td><?= date('h:m', strtotime($model->created)) ?></td>
        </tr>
    </table>
    Dengan hasil sebagai berikut : 
    <table class="hasil" style="font-weight: bolder; margin-top: 20px; margin-bottom: 20px; width: 100%;  border-collapse: collapse" border="1">
        <tr>
            <td>No.</td>
            <td>Jenis Narkoba</td>
            <td align="center">Hasil</td>
        </tr>
        <?php $no = 1; 
        foreach ($model->rmLabNapzaDetails as $key => $value): ?>
        <tr>
            <td align="center"><?= $no++ ?></td>
            <td><?= $value->periksa->periksa_nama ?></td>
            <td align="center" width="30%"><?= ($value->hasil == 1)? "<strong>Positif</strong>":"<strong>Negatif</strong>" ?></td>
        </tr>
        <?php endForeach; ?>
    </table>
    Demikian surat keterangan ini dibuat untuk keperluan : .

    <table style="right:0px; width: 100%">
        <tr>
            <td rowspan="4" width="60%"></td>
            <td style="text-align: center;">Tanjung Redep, <?= date('d F Y', strtotime($model->tanggal_surat)) ?></td>
        </tr>
        <tr>
            <td style="text-align: center;">Mengetahui, <br>Kepala Instalasi Laboratorium Klinik</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <u>dr. Suriani Syam,</u><br/>
                NIP. 19760226 200604 2 022
            </td>
        </tr>
    </table>        
</p>
<script type="text/javascript">
    window.onload = function() { window.print(); window.close(); };
</script>