<?php 
use app\models\Rl;

$this->title = 'Kunjungan Rawat Darurat : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Jenis Pelayanan</th>
			<th colspan="2">Total Pasien</th>
			<th colspan="3">Tindak Lanjut Pelayanan</th>
			<th rowspan="2">Mati di IGD</th>
			<th colspan="2">DOA</th>
		</tr>
		<tr>
			<th>Rujukan</th>
			<th>Non Rujukan</th>
			<th>Dirawat</th>
			<th>Dirujuk</th>
			<th>Pulang</th>
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
		</tr>
	</thead>
	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['jenis_layanan']?></td>
				<td><?= $val['pasien_rujukan']?></td>
				<td><?= $val['pasien_non_rujukan']?></td>
				<td><?= $val['dirawat']?></td>
				<td><?= $val['dirujuk']?></td>
				<td><?= $val['pulang']?></td>
				<td><?= $val['mati_igd']?></td>
				<td><?= $val['doa']?></td>
			</tr>	
		<?php endforeach; ?>

	</tbody>
</table>