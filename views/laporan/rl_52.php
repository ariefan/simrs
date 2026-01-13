<?php
$this->title = 'Pengunjung';
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Jenis Kegiatan</th>
			<th>Jumlah</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $key=>$val): ?>
		<tr>
			<td><?= $key+1 ?></td>
			<td><?= $val['medunit_nm'] ?></td>
			<td><?= $val['jumlah'] ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>