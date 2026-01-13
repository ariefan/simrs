<?php
$this->title = 'Penyakit Rawat Jalan';
?>

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="3">NO Urut</th>
			<th rowspan="3">NO DTD</th>
			<th rowspan="3">NO DAFTAR TERPERINCI</th>
			<th rowspan="3">GOLONGAN SEBAB PENYAKIT</th>
			<th colspan="18">JUMLAH PASIEN KASUS MENURUT GOLONGAN UMUR DAN SEX</th>
			<th rowspan="2" colspan="3">KASUS BARU MENURUT JENIS KELAMIN</th>
			<th rowspan="3">JUMLAH KUNJUNGAN</th>
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
			<th>JMLH</th>
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