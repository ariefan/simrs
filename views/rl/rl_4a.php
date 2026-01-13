<?php
$this->title = 'Penyakit Rawat Inap';
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="3">No. Urut</th>
			<th rowspan="3">No. DTD</th>
			<th rowspan="3">No. Daftar terperinci</th>
			<th rowspan="3">Golongan Sebab Penyakit</th>
			<th colspan="18">Jumlah Pasien Hidup dan Mati menurut Golongan Umur & Jenis Kelamin</th>
			<th rowspan="2" colspan="2">Pasien Keluar (Hidup & Mati) Menurut Jenis Kelamin</th>
			<th rowspan="3">Jumlah Pasien Keluar Hidup & Mati(23+24)</th>
			<th rowspan="3">Jumlah Pasien Keluar Mati</th>
		</tr>
		<tr>
			<th colspan="2">0-6HR</th>
			<th colspan="2">7-28HR</th>
			<th colspan="2">28HR-1THN</th>
			<th colspan="2">1-4THN</th>
			<th colspan="2">5-14THN</th>
			<th colspan="2">15-24THN</th>
			<th colspan="2">25-44THN</th>
			<th colspan="2">45-64THN</th>
			<th colspan="2">>65THN</th>
		</tr>
		<tr>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
			<th>L</th>
			<th>P</th>
		</tr>
		<tr>
			<?php 
			for($i=1;$i<=26;$i++) echo "<th>$i</th>";
			?>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>