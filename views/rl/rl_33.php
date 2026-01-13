<?php 
use app\models\Rl;

$this->title = 'Kegiatan Kesehatan Gigi dan Mulut : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>No.</th>
			<th>Jenis Kegiatan</th>
			<th>Jumlah</th>
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
				<td><?= $val['jumlah']?></td>
			</tr>	
		<?php endforeach; ?>

	</tbody>
</table>