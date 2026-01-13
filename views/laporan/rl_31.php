<?php

$this->title = 'Kegiatan Pelayanan Rawat Inap';
if (count($data>0)):
?>

<div class="site-index">
<table class="table table-bordered">
<thead>
	<tr>
	<?php 
	foreach ($data[0] as $key => $value) 
	{?>
		<td><?= $key ?></td>
	<?php } ?>
	</tr>
</thead>
<tbody>
	<?php 
	for ($i=0; $i<count($data); $i++)
	{
		echo '<tr>';
		foreach ($data[$i] as $key => $value)
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	?>
</tbody>
</table>
</div>
<?php endif; ?>