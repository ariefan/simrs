<?php
use app\models\Konfigurasi;
$this->title = 'CASE-MIX PASIEN';

$k = Konfigurasi::findOne('PETUGAS_KODER');

?>

<table class="table">
	<tr>
		<th style="padding:1px;border: none">No. MR</th>
		<td style="padding:1px;border: none">:</td>
		<td style="padding:1px;border: none"><?= $model->mr ?></td>
		<td style="padding:1px;border: none"></td>
		<td style="padding:1px;border: none"></td>
		<td style="padding:1px;border: none"></td>
		<td style="padding:1px;border: none"></td>
	</tr>
	<tr>
		<th style="padding:1px;border: none">Nama Peserta</th>
		<td style="padding:1px;border: none">:</td>
		<td style="padding:1px;border: none"><?= $pasien->nama ?></td>
		<th style="padding:1px;border: none">Jenis Kelamin</th>
		<td style="padding:1px;border: none">:</td>
		<td style="padding:1px;border: none"><?= $pasien->jk ?></td>
	</tr>
	<tr>
		<th style="padding:1px;border: none">Alamat</th>
		<td style="padding:1px;border: none">:</td>
		<td style="padding:1px;border: none"><?= $pasien->alamat ?></td>
		<th style="padding:1px;border: none">Tanggal Lahir</th>
		<td style="padding:1px;border: none">:</td>
		<td style="padding:1px;border: none"><?= $pasien->tanggal_lahir ?></td>
	</tr>
</table>
<hr/>

<table class="table">
	<tr>
		<td style="padding:1px;border: none">1.</td>
		<td colspan="2" style="padding:1px;border: none">Anamnese:</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"></td>
		<td colspan="2" style="padding:1px;border: none"><?= $model->anamnesis ?></td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">2.</td>
		<td colspan="2" style="padding:1px;border: none">Kronologi untuk kasus cedera, keracunan dan beberapa konsekuensi lain dari sebab eksternal:</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"></td>
		<td colspan="2" style="padding:1px;border: none"></td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">3.</td>
		<td style="padding:1px;border: none">Diagnosa Utama:</td>
		<td style="padding:1px;border: none"><?= $model->assesment ?></td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"></td>
		<td colspan="2" style="padding:1px;border: none">
			<ul>
            <?php foreach($rm_diagnosis as $value): ?>
                <li><?= !empty($value['kode']) ? $value['kode']." - ".$value['nama_diagnosis']." (".$value['kasus'].")" : $value['nama_diagnosis'] ?></li>
            <?php endforeach; ?>
            </ul>
		</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">4.</td>
		<td style="padding:1px;border: none">Diagnosa Sekunder:</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"></td>
		<td colspan="2" style="padding:1px;border: none">
			<ul>
            <?php foreach($rm_diagnosis_banding as $value): ?>
                <li><?= !empty($value['kode']) ? $value['kode']." - ".$value['nama_diagnosis']." (".$value['kasus'].")" : $value['nama_diagnosis'] ?></li>
            <?php endforeach; ?>
            </ul>
		</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">5.</td>
		<td style="padding:1px;border: none">Tindakan:</td>
		<td style="padding:1px;border: none"><?= $model->assesment ?></td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"></td>
		<td style="padding:1px;border: none">
			<ul>
            <?php foreach($rm_tindakan as $value): ?>
                <li><?= $value['nama_tindakan'] ?></li>
            <?php endforeach; ?>
            </ul>
		</td>
		<td style="padding:1px;border: none">
			<ul>
            <?php foreach($rm_icd9 as $value): ?>
                <li><?= $value['kode'] ?> (<?= $value['long_desc'] ?>)</li>
            <?php endforeach; ?>
            </ul>
		</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">6.</td>
		<td style="padding:1px;border: none">Konsultasi</td>
		<td style="padding:1px;border: none">1. Tidak &nbsp;&nbsp; 2. Ya, Spesialis ______________</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">7.</td>
		<td style="padding:1px;border: none">Tanggal Pulang</td>
		<td style="padding:1px;border: none"></td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">8.</td>
		<td style="padding:1px;border: none">Cara Pulang</td>
		<td style="padding:1px;border: none">1. Sembuh &nbsp;&nbsp; 2. Pulang Paksa &nbsp;&nbsp; 3. Meninggal &nbsp;&nbsp; 4. Rujuk, ke _________</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">9.</td>
		<td style="padding:1px;border: none">Biaya</td>
		<td style="padding:1px;border: none">Rp.</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none">10.</td>
		<td style="padding:1px;border: none">No. HP</td>
		<td style="padding:1px;border: none"></td>
	</tr>
	
</table>

<table class="table">
	<tr>
		<td style="text-align:center">Petugas Medis / Koder</td>
		<td style="text-align:center">DPJP</td>
	</tr>
	<tr>
		<td style="padding:1px;border: none"><br/><br/><br/><br/></td>
		<td style="padding:1px;border: none"><br/><br/><br/><br/></td>
	</tr>
	<tr>
		<td style="text-align:center"><?= $k->KONF_NILAI ?></td>
		<td style="text-align:center"><?= $kunjungan->dokter->nama ?></td>
	</tr>
</table>