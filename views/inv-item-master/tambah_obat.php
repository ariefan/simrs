<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h3>Cari Obat</h3>
<input type="text" id="cari-obat" placeholder="Ketik Nama Obat" class="form-control">
<br/>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Nama</th>
			<th>Stok</th>
			<th>Satuan</th>
			<th>Generic</th>
			<th></th>
		</tr>
	</thead>
	<tbody id="hasil-obat">
		
	</tbody>
</table>


<?php
$urlCari = Url::to(['inv-item-master/cari-obat']);
$target_table = ($tipe=='biasa') ? 'obat-rm' : 'obat-rm-racik-'.$counter;
$name_input = ($tipe=='biasa') ? 'Obat' : "ObatRacik[$counter]"; 
$str_signa = ($tipe=='biasa') ? 'yes': 'no' ; 
$script = <<< JS
    $(function(){
    	$(window).keydown(function(event){
		    if(event.keyCode == 13) {
		      event.preventDefault();
		      return false;
		    }
		  });
		  
        $('#cari-obat').keyup(function(){
        	if($(this).val().length<2) {
        		$('#hasil-obat').html("");
        		return false;
        	}
            $.post('{$urlCari}',{keyword:$(this).val()})
	            .done(function(data){
	              data = JSON.parse(data);
	              str_hasil = ""
	              for(var i in data){
	                str_hasil += "<tr><td>"+data[i].item_nm+"</td><td>"+data[i].qty+"</td><td>"+data[i].unit_nm+"</td><td>"+data[i].generic+"</td><td><button type='button' class='pilih-obat btn btn-primary' item_cd="+data[i].item_cd+" unit_cd="+data[i].unit_nm+" item_nm='"+data[i].item_nm+"'>Pilih</button>  </td></tr>"
	              }
	              $('#hasil-obat').html(str_hasil)
	              $('.pilih-obat').on('click',function(){
	                    item_cd = $(this).attr('item_cd')
	                    item_nm = $(this).attr('item_nm')
	                    unit_cd = $(this).attr('unit_cd')
	                    str_obat = "<tr>";
	                    str_obat += "<td>"+item_nm+"</td>";
	                    str_obat += "<td><input type='number' class='form-control' required placeholder='jumlah' name='{$name_input}[jumlah]["+item_cd+"]'></td>";
	                    str_obat += "<td>"+unit_cd+"</td>";
	                    if('{$str_signa}'=='yes')
	                    str_obat += "<td><input type='text' class='form-control' required placeholder='signa' name='{$name_input}[signa]["+item_cd+"]'></td>";
	                    str_obat += "<td><button type='button' class='btn btn-danger delete-item'>x</button></td>";
	                    str_obat += "</tr>";

	                    $('#{$target_table}').append(str_obat);

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