<?php
$this->title = 'RUJUKAN : Tahun '.$rl->tahun;
//echo '<pre>';print_r($data);exit;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2">JENIS SPESIALISASI</th>
			<th colspan="6">RUJUKAN</th>
			<th colspan="3">DIRUJUK</th>
		</tr>
		<tr>
			<th>DITERIMA DARI PUSKESMAS</th>
			<th>DITERIMA DARI FASKES LAIN</th>
			<th>DITERIMA DARI RS LAIN</th>
			<th>DIKEMBALIKAN KE PUSKESMAS</th>
			<th>DIKEMBALIKAN KE FASKES LAIN</th>
			<th>DIKEMBALIKAN KE RS ASAL</th>
			<th>PASIEN RUJUKAN</th>
			<th>DATANG SENDIRI</th>
			<th>DITERIMA KEMBALI</th>
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
			<th>11</th>
		</tr>
	</thead>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['jenis_spesialisasi']?></td>
				<td><?= $val['rk_3']?></td>
				<td><?= $val['rk_4']?></td>
				<td><?= $val['rk_5']?></td>
				<td><?= $val['rk_6']?></td>
				<td><?= $val['rk_7']?></td>
				<td><?= $val['rk_8']?></td>
				<td><?= $val['rk_9']?></td>
				<td><?= $val['rk_10']?></td>
				<td><?= $val['rk_11']?></td>
			</tr>	
		<?php endforeach; ?>
	<tbody>
		
	</tbody>
</table>