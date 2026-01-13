<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Inventory extends Model
{
	public $tahun;
    public $jenis_laporan = [
        "resep_obat_detail" => "Rekap Peresepan Obat",
        "pengunaan_obat_detail" => "Rekap Keuangan Obat Detail",
    ];

    public function getHasilLaporan($report_name,$start_date,$end_date){        
        if($report_name=="pengunaan_obat_detail"){
            $sql = $this->sqlPenggunaanObatDetail($start_date,$end_date);
        } elseif($report_name=="resep_obat_detail"){
            $sql = $this->sqlResep($start_date,$end_date);
        }

        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);     
        return $command->queryAll();
    }

    public function sqlPenggunaanObatDetail($start_date,$end_date){
    	return "SELECT 
				  no_invoice AS `No Invoice`,
				  tanggal_bayar AS `Tanggal Bayar`,
				  nama_obat AS `Nama Obat`,
				  jumlah AS `Jumlah`,
				  harga_satuan AS `Harga Satuan`,
				  harga_total AS `Harga Total` 
				FROM
				  bayar 
				  JOIN bayar_obat USING (no_invoice) 
				WHERE DATE(tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
    }

    public function sqlResep($start_date,$end_date){
    	return "
	    	SELECT 
			  dokter.`nama` AS `Dokter`,
			  pasien.`mr` AS `Nomor RM`,
			  pasien.`nama` AS `Nama Pasien`,
			  kunjungan.`tanggal_periksa` AS `Tanggal Kunjungan`,
			  nama_obat AS `Nama Obat`, 
			  jumlah AS `Jumlah`,
			  satuan AS `Satuan`
			FROM
			  kunjungan 
			  JOIN rekam_medis USING (kunjungan_id) 
			  JOIN rm_obat USING (rm_id) 
			  JOIN dokter 
			    ON kunjungan.`dpjp` = dokter.`user_id` 
			  JOIN pasien 
			    ON pasien.`mr` = kunjungan.`mr` 
			WHERE DATE(tanggal_periksa) BETWEEN '$start_date' AND '$end_date'
			ORDER BY kunjungan_id 
			
	    ";
    }
}