<?php 
use app\models\Rl;

$this->title = 'Kunjungan Perinatologi : Tahun '.$rl->tahun;
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="3">NO.</th>
			<th rowspan="3">JENIS KEGIATAN</th>
			<th colspan="8">RUJUKAN</th>
			<th colspan="2">NON RUJUKAN</th>
			<th rowspan="3">DIRUJUK</th>
		</tr>
		<tr>
			<th colspan="6">MEDIS</th>
			<th colspan="2">NON MEDIS</th>
			<th rowspan="2">Mati</th>
			<th rowspan="2">Jumlah Total</th>
		</tr>
		<tr>
			<th>RUMAH SAKIT</th>
			<th>BIDAN</th>
			<th>PUSKESMAS</th>
			<th>FASKES LAINNYA</th>
			<th>Mati</th>
			<th>Jumlah Total</th>
			<th>Mati</th>
			<th>Jumlah Total</th>
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
			<th>12</th>
			<th>13</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($nilai as $key=>$val): ?>
			<tr>
				<td><?= $val['no']?></td>
				<td><?= $val['jenis_kegiatan']?></td>
				<td><?= $val['rumah_sakit']?></td>
				<td><?= $val['bidan']?></td>
				<td><?= $val['puskesmas']?></td>
				<td><?= $val['lain_lain']?></td>

				<td><?= $val['mati_medis']?></td>
				<td><?= $val['total_medis']?></td>

				<td><?= $val['mati_non_medis']?></td>
				<td><?= $val['total_non_medis']?></td>

				<td><?= $val['mati_non_rujukan']?></td>
				<td><?= $val['total_non_rujukan']?></td>
				<td><?= $val['dirujuk']?></td>
			</tr>	
		<?php endforeach; ?>
	</tbody>
</table>