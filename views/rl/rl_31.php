<?php 
use app\models\Rl;

$this->title = 'Kegiatan Layanan Harian Rawat Inap : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Jenis Layanan</th>
			<th rowspan="2">Pasien Awal Tahun</th>
			<th rowspan="2">Pasien Masuk</th>
			<th rowspan="2">Pasien Keluar Hidup</th>
			<th colspan="2">Pasien Keluar Mati</th>
			<th rowspan="2">Jumlah Lama Dirawat</th>
			<th rowspan="2">Pasien Akhir Tahun</th>
			<th rowspan="2">Jumlah Hari Perawatan</th>
		</tr>
		<tr>
			<th>Mati < 48 Jam</th>
			<th>Mati > 48 Jam</th>
		</tr>
		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
			<th>8</th>
			<th>9</th>
			<th>10</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['jenis_layanan']?></td>
				<td><?= $val['pasien_awal_tahun']?></td>
				<td><?= $val['pasien_masuk']?></td>
				<td><?= $val['pasien_hidup']?></td>
				<td><?= $val['pasien_mati1']?></td>
				<td><?= $val['pasien_mati2']?></td>
				<td><?= $val['lama_dirawat']?></td>
				<td><?= $val['pasien_akhir_tahun']?></td>
				<td><?= $val['jumlah_hp']?></td>
			</tr>	
		<?php endforeach; ?>

	</tbody>
</table>