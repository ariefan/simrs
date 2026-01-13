<?php 
use app\models\Rl;

$this->title = 'BOR, aLOS, BTO, TOI, NDR, GDR : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Tahun</th>
			<th>BOR</th>
			<th>LOS</th>
			<th>BTO</th>
			<th>TOI</th>
			<th>NDR</th>
			<th>GDR</th>
			<th>Rata-rata Kunjungan/Hari</th>
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
				<td><?= $val['tahun']?></td>
				<td><?= round($val['bor'], 2)." %"?></td>
				<td><?= $val['los']?></td>
				<td><?= $val['bto']?></td>
				<td><?= $val['toi']?></td>
				<td><?= $val['ndr']?></td>
				<td><?= $val['gdr']?></td>
				<td><?= round($val['rata'], 2)?></td>
			</tr>	
		<?php endforeach; ?>

	</tbody>
</table>