<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h3>Cari Makanan</h3>
<input type="text" id="cari-makanan" placeholder="Ketik Nama Makanan" class="form-control">
<br/>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Kode Diet</th>
			<th>Bahan Makanan</th>
			<th>Kandungan</th>
			<th>Jumlah Gizi</th>
			<th>Deskripsi</th>
			<th></th>
		</tr>
	</thead>
	<tbody id="hasil-makanan">
		
	</tbody>
</table>


<?php
$urlCari = Url::to(['gizi-diet/cari-makanan']);
$target_table = 'makanan-rm'; 
$name_input = 'Makanan'; 
$str_signa = 'yes'; 
$script = <<<JS
    $(function(){
        $('#cari-makanan').keyup(function(){
        	if($(this).val().length<2) {
        		$('#hasil-makanan').html("");
        		return false;
        	}
			
            $.post('{$urlCari}',{ q: $(this).val() })
	            .done(function(data){
	              data = JSON.parse(data);
	              str_hasil = ""
	              for(var i in data){
	                str_hasil += "<tr><td>"+data[i].kode_diet+"</td><td>"+data[i].bahan_makanan+"</td><td>"+data[i].kandungan+"</td><td>"+data[i].jumlah_gizi+"</td><td>"+data[i].deskripsi+"</td><td><button type='button' class='pilih-makanan btn btn-primary' kode_diet="+data[i].kode_diet+" bahan_makanan="+data[i].bahan_makanan+" kandungan='"+data[i].kandungan+"'>Pilih</button>  </td></tr>"
	              }
	              $('#hasil-makanan').html(str_hasil)
	              $('.pilih-makanan').on('click',function(){
	                    kode_diet = $(this).attr('kode_diet')
	                    bahan_makanan = $(this).attr('bahan_makanan')
	                    kandungan = $(this).attr('kandungan')
	                    str_makanan = "<tr>";
	                    str_makanan += "<td>"+bahan_makanan+"</td>";
	                    str_makanan += "<td><input type='number' class='form-control' required placeholder='jumlah' name='{$name_input}[jumlah]["+item_cd+"]'></td>";
	                    str_makanan += "<td>"+unit_cd+"</td>";
	                    if('{$str_signa}'=='yes')
	                    str_makanan += "<td><input type='text' class='form-control' required placeholder='signa' name='{$name_input}[signa]["+item_cd+"]'></td>";
	                    str_makanan += "<td><button type='button' class='btn btn-danger delete-item'>x</button></td>";
	                    str_makanan += "</tr>";

	                    $('#{$target_table}').append(str_makanan);

	                    $('.delete-item').click(function(){
				            $(this).parent().parent().remove()
				        })

				        $(this).parent().parent().remove()
	              })
	              
	            });
	        })
    });

JS;

$this->registerJs($script);
?>