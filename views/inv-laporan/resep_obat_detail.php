<?php 
$this->title = 'Rekap Peresepan Obat: ';
$this->title .= \app\models\Jadwal::tanggal_indo($post_data['start_date']).' s/d '.\app\models\Jadwal::tanggal_indo($post_data['end_date']);

if(!empty($data)): 

?>
<table class="table">
	<thead>
		<tr>
			<?php foreach(array_keys($data[0]) as $v): ?>
			<th style="text-align: center;"><?= $v ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $v): ?>
		<tr>
			<?php foreach(array_keys($data[0]) as $vk): ?>
				<?php if(is_numeric($v[$vk])): ?>
				<td style="text-align: right;"><?= number_format($v[$vk],0,'','.') ?></td>
				<?php else: ?>
				<td style="text-align: center;"><?= $v[$vk] ?></td>
				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>

Tidak Ada Data

<?php endif; ?>