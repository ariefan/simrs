<?php 
use app\models\Rl;

$this->title = 'Kunjungan Pembedahan : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>NO</th>
			<th>SPESIALISASI</th>
			<th>TOTAL</th>
			<th>KHUSUS</th>
			<th>BESAR</th>
			<th>SEDANG</th>
			<th>KECIL</th>
		</tr>
		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['spesialisasi']?></td>
				<td><?= $val['total']?></td>
				<td><?= $val['khusus']?></td>
				<td><?= $val['besar']?></td>
				<td><?= $val['sedang']?></td>
				<td><?= $val['kecil']?></td>
			</tr>	
		<?php endforeach; ?>
	</tbody>
</table>
