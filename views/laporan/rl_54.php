<?php
$this->title = '10 Besar Penyakit Rawat Jalan';
//echo '<pre>';print_r($data);exit;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">No Urut</th>
			<th rowspan="2">Kode ICD-10</th>
			<th rowspan="2">Deskripsi</th>
			<th colspan="2">Kasus Baru menurut Jenis Kelamin</th>
			<th rowspan="2">Jumlah Kasus Baru (4+5)</th>
			<th rowspan="2">Jumlah Kunjungan</th>
		</tr>
		<tr>
			<th>LK</th>
			<th>PR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val): ?>
			<tr>
				<td><?= $key+1 ?></td>
				<td><?= $val['icd_cd'] ?></td>
				<td><?= $val['icd_nm'] ?></td>
				<td><?= $val['jumlah_pria'] ?></td>
				<td><?= $val['jumlah_wanita'] ?></td>
				<td><?= $val['jumlah'] ?></td>
				<td><?= $val['jumlah'] ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

