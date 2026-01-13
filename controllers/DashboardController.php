<?php

namespace app\controllers;
use Yii;

class DashboardController extends \yii\web\Controller
{
    public function actionIndex()
    {

    	$this->layout = 'main_no_portlet';
        $hari_ini = [];
        $bulan_ini = [];
        $diagnosis_tahun_ini = [];
    	$diagnosis_bulan_ini = [];
        $kunjungan_bulan = [];
        $bulan = [];
        $hari_ini = $this->getDataKunjungan(date('Y-m-d'),date('Y-m-d'));
    	$bulan_ini = $this->getDataKunjungan(date('Y-m-').'01',date("Y-m-t", strtotime(date('Y-m-d'))));

    	// $diagnosis_tahun_ini = $this->getDataDiagnosis(date('Y').'-01-01',date('Y').'-12-31');
    	// $diagnosis_bulan_ini = $this->getDataDiagnosis(date('Y-m-').'01',date("Y-m-t", strtotime(date('Y-m-d'))));
        
        $kunjungan_bulan = $this->getKunjunganPerBulan();
        
        $bulan = $this->getBulan(count($kunjungan_bulan));

        return $this->render('index',compact('hari_ini','bulan_ini','diagnosis_tahun_ini','diagnosis_bulan_ini','kunjungan_bulan','bulan'));
    }

    public function getKunjunganPerBulan()
    {
        $connection = Yii::$app->db;
        $sql = "SELECT 
                  YEAR(tanggal_periksa) AS THN,
                  MONTH(tanggal_periksa) AS BLN,
                  COUNT(1) AS JML 
                FROM
                  kunjungan 
                WHERE YEAR(tanggal_periksa) = YEAR(NOW()) 
                GROUP BY YEAR(tanggal_periksa),
                  MONTH(tanggal_periksa)
                ORDER BY THN,BLN
                ";
        //$sql = "CALL getKunjunganByJK('$tgl_start','$tgl_end')";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {
            $d[] = intval($value['JML']);
        }
        return $d;
    }

    public function getBulan($jml){
        $bln = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

        return array_splice($bln, 0, $jml);
    }

    public function getDataKunjungan($tgl_start,$tgl_end)
    {
    	$connection = Yii::$app->db;
        $sql = "SELECT 
                  pasien.jk,
                  COUNT(1) AS JML 
                FROM
                  kunjungan 
                  JOIN pasien 
                    ON kunjungan.mr = pasien.mr 
                WHERE kunjungan.`tanggal_periksa` BETWEEN '$tgl_start'
                  AND '$tgl_end'
                GROUP BY pasien.jk";
    	//$sql = "CALL getKunjunganByJK('$tgl_start','$tgl_end')";
    	$command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {
        	$d[] = intval($value['JML']);
        }
        return $d;
    }

    public function getDataDiagnosis($tgl_start,$tgl_end)
    {
    	$connection = Yii::$app->db;
    	$sql = "SELECT 
                    CONCAT(kode, ' - ', `nama_diagnosis`) AS diagnosis,
                    COUNT(1) AS jml 
                  FROM
                    rm_diagnosis 
                    JOIN rekam_medis USING (rm_id) 
                    JOIN kunjungan USING (kunjungan_id) 
                  WHERE kunjungan.`tanggal_periksa` BETWEEN '$tgl_start' 
                    AND '$tgl_end' 
                  GROUP BY kode 
                  ORDER BY jml DESC LIMIT 10";
        //$sql = "CALL getTop10DiagnosisPerTgl('$tgl_start','$tgl_end')";
    	$command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

        	$d[$key]['name'] = strlen($value['diagnosis'])>50 ? substr($value['diagnosis'],0,50).'....' : $value['diagnosis'] ;
        	$d[$key]['y'] = intval($value['jml']);
        }
        return $d;
    }

}
