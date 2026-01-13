<?php
$this->title = 'Pengunjung';
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>No</th>		
			<th>Jenis Kegiatan</th>		
			<th>Jumlah</th>		
			<th>Keterangan</th>		
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Pengunjung Baru</td>
			<td><?= $data[0]['jumlah_baru'] ?></td>
			<td>RAWAT JALAN DAN RAWAT INAP</td>
		</tr>
		<tr>
			<td>2</td>
			<td>Pengunjung Lama</td>
			<td><?= $data[0]['jumlah_lama'] ?></td>
			<td>RAWAT JALAN DAN RAWAT INAP</td>
		</tr>
	</tbody>
</table>