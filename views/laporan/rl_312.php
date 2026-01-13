<?php
$this->title = 'Keluarga Berencana';
//echo '<pre>';print_r($data);exit;
?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2">METODA</th>
			<th colspan="2">KONSELING</th>
			<th colspan="4">KB BARU DENGAN CARA MASUK</th>
			<th colspan="3">KB BARU DENGAN KONDISI</th>
			<th rowspan="2">KUNJUNGAN ULANG</th>
			<th colspan="2">KELUHAN EFEK SAMPING</th>
		</tr>
		<tr>
			<th>ANC</th>
			<th>PASCA PERSALINAN</th>
			<th>BUKAN RUJUKAN</th>
			<th>RUJUKAN RAWAT INAP</th>
			<th>RUJUKAN RAWAT JALAN</th>
			<th>TOTAL</th>
			<th>PASCA PERSALINAN / NIFAS</th>
			<th>ABORTUS</th>
			<th>LAINNYA</th>
			<th>JUMLAH</th>
			<th>DIRUJUK</th>
		</tr>
		<tr>
			<?php 
			for($i=1;$i<=14;$i++) echo "<th>$i</th>";
			?>
		</tr>
	</thead>

	<tbody>
		
	</tbody>
</table>
