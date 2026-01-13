<?php
$this->title = '10 Besar Penyakit Rawat Jalan: Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">No Urut</th>
			<th rowspan="2">Kode ICD-10</th>
			<th rowspan="2">Deskripsi</th>
			<th colspan="2">Pasien Keluar Hidup Menurut Jenis Kelamin</th>
			<th colspan="2">Pasien Keluar Mati Menurut Jenis Kelamin</th>
			<th rowspan="2">Total (Hidup & Mati)</th>
		</tr>
		<tr>
			<th>LK</th>
			<th>PR</th>
			<th>LK</th>
			<th>PR</th>
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
		</tr>
	</thead>
	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['kode']?></td>
				<td><?= $val['deskripsi']?></td>
				<td><?= $val['nilai_4']?></td>
				<td><?= $val['nilai_5']?></td>
				<td><?= $val['nilai_6']?></td>
				<td><?= $val['nilai_7']?></td>
			</tr>	
		<?php endforeach; ?>	
	</tbody>
</table>

