<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RL MILIK RSUD WONOSARI
 */
class Keuangan extends Model
{
	public $tahun;
    public $jenis_laporan = [
        "pengunaan_obat_detail" => "Rekap Penggunaan Obat Detail",
        "rekap_lab" => "Rekap Pelayanan Laboratorium",
        "rekap_rad" => "Rekap Pelayanan Radiologi",
        "rekap_tindakan" => "Rekap Pelayanan Tindakan",
    ];

    public function getHasilLaporan($report_name,$start_date,$end_date){        
        if($report_name=="pengunaan_obat_detail"){
            $sql = $this->sqlPenggunaanObatDetail($start_date,$end_date);
        } elseif($report_name=="rekap_lab"){
            $sql = $this->sqlLab($start_date,$end_date);
        } elseif($report_name=="rekap_rad"){
            $sql = $this->sqlRad($start_date,$end_date);
        } elseif($report_name=="rekap_tindakan"){
            $sql = $this->sqlTindakan($start_date,$end_date);
        }

        // echo $sql;exit;
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

    public function sqlTindakan($start_date,$end_date){
    	return "SELECT 
				  no_invoice AS `No Invoice`,
				  tanggal_bayar AS `Tanggal Bayar`,
				  nama_tindakan AS `Nama Tindakan`,
				  harga AS `Harga`
				FROM
				  bayar 
				  JOIN bayar_tindakan USING (no_invoice) 
				WHERE DATE(tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
    }

    public function sqlLab($start_date,$end_date){
    	return "SELECT 
				  no_invoice AS `No Invoice`,
				  tanggal_bayar AS `Tanggal Bayar`,
				  nama_lab AS `Nama Penunjang`,
				  harga AS `Harga`
				FROM
				  bayar 
				  JOIN bayar_lab USING (no_invoice)
				WHERE DATE(tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
    }

    public function sqlRad($start_date,$end_date){
    	return "SELECT 
				  no_invoice AS `No Invoice`,
				  tanggal_bayar AS `Tanggal Bayar`,
				  nama_rad AS `Nama Penunjang`,
				  harga AS `Harga`
				FROM
				  bayar 
				  JOIN bayar_rad USING (no_invoice)
				WHERE DATE(tanggal_bayar) BETWEEN '$start_date' AND '$end_date'";
    }
}