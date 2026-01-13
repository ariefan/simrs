<?php 
use app\models\Rl;

$this->title = 'Kunjungan Laboratorium : Tahun '.$rl->tahun;
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
				<td><?= $val['kegiatan']?></td>
				<td><?= $val['jumlah']?></td>
			</tr>	
		<?php endforeach; ?>
	</tbody>
</table>
