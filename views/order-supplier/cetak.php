<?php use yii\helpers\Html; 
	$this->title = 'Cetak Laporan';
	?>
	<title><?= $this->title ?></title>
<?php if($stat=='ordered')
{ ?>
<table border="0">
	<tr>
		<td rowspan="5"><?= Html::img('@web/adminlte/img/logo.png', ['height'=>100]) ?></td>
		<td colspan="2"><strong>UNIVERSITAS MUHAMMADIYAH PURWOKERTO</strong></td>
	</tr>
	<tr>
		<td colspan="2">Kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182</td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239</td>
	</tr>
	<tr>
		<td  colspan="2">Kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181</td>
	</tr>
	<tr>
		<td></td>
		<td>Telp. (0281) 6844252, 6844253, Fax. (0281) 67239</td>
	</tr>
	<tr height="100">
		<td></td>
		<td  colspan="2"><strong>DAFTAR USULAN BAHAN LABORATORIUM</strong></td>
	</tr>
</table>
<?php }
elseif ($stat=='approved') 
{ ?>
<table border="0">
	<tr>
		<td rowspan="5"><?= Html::img('@web/adminlte/img/logo.png', ['height'=>100]) ?></td>
		<td colspan="2"><strong>UNIVERSITAS MUHAMMADIYAH PURWOKERTO</strong></td>
	</tr>
	<tr>
		<td colspan="2">Kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182</td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239</td>
	</tr>
	<tr>
		<td  colspan="2">Kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181</td>
	</tr>
	<tr>
		<td></td>
		<td>Telp. (0281) 6844252, 6844253, Fax. (0281) 67239</td>
	</tr>
	<tr height="100">
		<td></td>
		<td  colspan="2"><strong>BERITA ACARA PENYERAHAN BARANG</strong><br/>
			Nomor : ...</td>
	</tr>
</table>
<?php }
else
{ ?>
<table border="0">
	<tr>
		<td rowspan="5"><?= Html::img('@web/adminlte/img/logo.png', ['height'=>100]) ?></td>
		<td colspan="2"><strong>UNIVERSITAS MUHAMMADIYAH PURWOKERTO</strong></td>
	</tr>
	<tr>
		<td colspan="2">Kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182</td>
	</tr>
	<tr>
		<td width="25"></td>
		<td>Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239</td>
	</tr>
	<tr>
		<td  colspan="2">Kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181</td>
	</tr>
	<tr>
		<td></td>
		<td>Telp. (0281) 6844252, 6844253, Fax. (0281) 67239</td>
	</tr>
	<tr height="100">
		<td></td>
		<td  colspan="2"><strong>LAMPIRAN SPK</strong><br/>
			Nomor : ...</td>
	</tr>
	<tr>
		<td  colspan="3">Pada hari .............. Di purwokerto, sesuai dengan surat No......... Tanggal......</td>
	</tr>
	<tr>
		<td  colspan="3">Telah terjadi penyerahan/ penerimaan barang anara :</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Nama :</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Jabatan : Pelaksanaan Bagian Inventaris</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Alamat : Kampus I UMP Dukuhwaluh, Purwokerto</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Sebagai Pihak yang menyerahkan barang</td>
	</tr>
	<tr height="30">
		<td></td>
		<td  colspan="2"></td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Nama :</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Jabatan : Kepala Laboratorium .....</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Alamat : Kampus I UMP Dukuhwaluh, Purwokerto</td>
	</tr>
	<tr>
		<td></td>
		<td  colspan="2">Sebagai Pihak yang menerima barang
</td>
	</tr>
</table>
<?php } ?>

<br/>
<table border="1" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<?php foreach ($HEADER as $key => $value) { ?>
			<th><strong><?= $value ?></strong></th>
		<?php	} ?>
	</tr>	
		<?php for ($i=0; $i <count($rows) ; $i++) { ?>
			<tr>
				<?php foreach ($rows[$i] as $key => $value) { ?>
					<td><?= $value ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
</table>