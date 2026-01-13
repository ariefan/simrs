<?php
$this->title = 'Cara Bayar : Tahun '.$rl->tahun;
//echo '<pre>';print_r($data);exit;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2">CARA PEMBAYARA</th>
			<th colspan="2">PASIEN RAWAT INAP</th>
			<th rowspan="2">JUMLAH PASIEN RAWAT JALAN</th>
			<th colspan="3">JUMLAH PASIEN RAWAT JALAN</th>
		</tr>
		<tr>
			<th>Jumlah Pasien Keluar</th>
			<th>Jumlah Lama Dirawat</th>
			<th>Laboratorium</th>
			<th>Radiologi</th>
			<th>Lain-lain</th>
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
				<td><?= $val['cara_bayar']?></td>
				<td><?= $val['rk_3']?></td>
				<td><?= $val['rk_4']?></td>
				<td><?= $val['rk_5']?></td>
				<td><?= $val['rk_6']?></td>
				<td><?= $val['rk_7']?></td>
				<td><?= $val['rk_8']?></td>
			</tr>	
		<?php endforeach; ?>		
	</tbody>
</table>