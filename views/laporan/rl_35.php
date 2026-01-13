<?php
$this->title = 'Kegiatan Perinatologi';
if (count($data>0)):
?>

<div class="site-index">
<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="3">NO</th>
			<th rowspan="3">JENIS KEGIATAN</th>
			<th colspan="10">RUJUKAN</th>
			<th colspan="3" rowspan="2">NON RUJUKAN</th>
			<th rowspan="3">DIRUJUK</th>
		</tr>
		<tr>
			<th colspan="7">RUJUKAN MEDIS</th>
			<th colspan="3">RUJUKAN NON MEDIS</th>
		</tr>
		<tr>
			<th>RUMAH SAKIT</th>
			<th>BIDAN</th>
			<th>PUSKESMAS</th>
			<th>FASKES LAINNYA</th>
			<th>HIDUP</th>
			<th>MATI</th>
			<th>TOTAL</th>
			<th>HIDUP</th>
			<th>MATI</th>
			<th>TOTAL</th>
			<th>HIDUP</th>
			<th>MATI</th>
			<th>TOTAL</th>
		</tr>
		<tr>
			<?php for ($i=1; $i<=16; $i++): ?>
			<th><?= $i ?></th>
			<?php endfor; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($shaped_data as $key => $value) { ?>
		<tr>
			<td><?= $value['no'] ?></td>
			<td><?= $value['nama'] ?></td>
			<td><?= $value['K_3'] ?></td>
			<td><?= $value['K_4'] ?></td>
			<td><?= $value['K_5'] ?></td>
			<td><?= $value['K_6'] ?></td>
			<td><?= $value['K_7'] ?></td>
			<td><?= $value['K_8'] ?></td>
			<td><?= $value['K_9'] ?></td>
			<td><?= $value['K_10'] ?></td>
			<td><?= $value['K_11'] ?></td>
			<td><?= $value['K_12'] ?></td>
			<td><?= $value['K_13'] ?></td>
			<td><?= $value['K_14'] ?></td>
			<td><?= $value['K_15'] ?></td>
			<td><?= $value['K_16'] ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<?php endif; ?>