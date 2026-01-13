<?php
$this->title = 'PELAYANAN KHUSUS : Tahun '.$rl->tahun;
//echo '<pre>';print_r($data);exit;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>NO</th>
			<th>JENIS KEGIATAN</th>
			<th>JUMLAH</th>
		</tr>
		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['jenis_kegiatan']?></td>
				<td><?= $val['rk_3']?></td>
			</tr>	
		<?php endforeach; ?>
	</tbody>
</table>