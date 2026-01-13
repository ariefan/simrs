<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Rl;
use app\models\Kunjungan;

class RlController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        $rl = new Rl();
        $jenis_laporan = $rl->jenis_laporan;

        if(!empty(Yii::$app->request->post())){
            $post_data = Yii::$app->request->post();
            // print_r($post_data);exit;
            $this->layout = 'report';
            $jns = $post_data['jenis_laporan'];
            $thn = $post_data['tahun'];
            $tgl_sekarang = getdate();
            $rl->tahun = $thn;

            if($jns=="rl_31"){
                $SQL = "SELECT no, jenis_pelayanan from rl_ref_31 order by no asc";
                $rl_31 = Kunjungan::findBySql($SQL)->asArray()->all();     
                foreach($rl_31 as $key=>$val):
                    $i=$val['no'];
                    $layanan=$val['jenis_pelayanan'];

                    $SQLpasien_awal_tahun="SELECT count(*) as jml from kunjungan where tipe_kunjungan='Rawat Inap' and
                    rl_31=$i and (year(date(jam_selesai))=$thn-1 or date(jam_selesai) is NULL)";
                    $pasien_awal_tahun=Kunjungan::findBySql($SQLpasien_awal_tahun)->asArray()->all();
                    $nilai_pasien_awal_tahun[$i]=0;
                    foreach($pasien_awal_tahun as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_awal_tahun[$i]=$val['jml'];
                        }  
                    endforeach;     

                    $SQLpasien_masuk="SELECT count(*) as jml from kunjungan where tipe_kunjungan='Rawat Inap' and
                    rl_31=$i and year(date(tanggal_periksa))=$thn";
                    $pasien_masuk=Kunjungan::findBySql($SQLpasien_masuk)->asArray()->all();
                    $nilai_pasien_masuk[$i]=0;
                    foreach($pasien_masuk as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_masuk[$i]=$val['jml'];
                        }  
                    endforeach;  

                    $SQLpasien_hidup="SELECT count(*) as jml from kunjungan where tipe_kunjungan='Rawat Inap' and
                    rl_31=$i and year(date(jam_selesai))=$thn and jenis_keluar='Hidup' ";
                    $pasien_hidup=Kunjungan::findBySql($SQLpasien_hidup)->asArray()->all();
                    $nilai_pasien_hidup[$i]=0;
                    foreach($pasien_hidup as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_hidup[$i]=$val['jml'];
                        }  
                    endforeach;  

                    $SQLpasien_mati1="SELECT count(*) as jml from kunjungan where tipe_kunjungan='Rawat Inap' and
                    rl_31=$i and year(date(jam_selesai))=$thn and jenis_keluar='Mati < 48 Jam' ";
                    $pasien_mati1=Kunjungan::findBySql($SQLpasien_mati1)->asArray()->all();
                    $nilai_pasien_mati1[$i]=0;
                    foreach($pasien_mati1 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_mati1[$i]=$val['jml'];
                        }  
                    endforeach;  

                    $SQLpasien_mati2="SELECT count(*) as jml from kunjungan where tipe_kunjungan='Rawat Inap' and
                    rl_31=$i and year(date(jam_selesai))=$thn and jenis_keluar='Mati > 48 Jam' ";
                    $pasien_mati2=Kunjungan::findBySql($SQLpasien_mati1)->asArray()->all();
                    $nilai_pasien_mati2[$i]=0;
                    foreach($pasien_mati2 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_mati2[$i]=$val['jml'];
                        }  
                    endforeach;  

                    $SQLlama_dirawat = "SELECT sum(datediff(date(jam_selesai),date(jam_masuk))+1) as lama_dirawat
                            FROM kunjungan WHERE year(date(jam_selesai))=$thn and rl_31=$i ";
                    $lama_dirawat = Kunjungan::findBySql($SQLlama_dirawat)->asArray()->all();
                    $nilai_lama_dirawat[$i]=0;
                    foreach($lama_dirawat as $key=>$val):
                        if(isset($val['lama_dirawat'])){
                            $nilai_lama_dirawat[$i]=$val['lama_dirawat'];
                        }
                    endforeach;    

                    $SQLpasien_akhir_tahun=" SELECT count(*) from kunjungan where tipe_kunjungan='Rawat Inap' and
                            (year(date(jam_selesai))>$thn or date(jam_selesai) is NULL ) and 
                            year(date(jam_masuk))<=$thn ";
                    $pasien_akhir_tahun = Kunjungan::findBySql($SQLpasien_akhir_tahun)->asArray()->all();
                    $nilai_pasien_akhir_tahun[$i]=0;
                    foreach($pasien_akhir_tahun as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_akhir_tahun[$i]=$val['jml'];
                        }
                    endforeach;                       
                    
                    if($thn==$tgl_sekarang['year']){
                        $SQL = "SELECT year(tanggal_periksa) as tahun,sum(CASE
                            WHEN 
                                (date(jam_selesai) is NULL) and 
                                (date(jam_masuk) >= concat(year(tanggal_periksa),'01/01') and 
                                date(jam_masuk) <= CURDATE())
                            THEN datediff(CURDATE(),date(jam_masuk))+1
                            WHEN 
                                (date(jam_selesai) is NULL) and 
                                (date(jam_masuk) < concat(year(tanggal_periksa),'/01/01'))
                            THEN datediff(CURDATE(),concat(year(tanggal_periksa),'01/01'))+1
                            WHEN 
                                (date(jam_selesai) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_selesai) <= CURDATE()) and
                                (date(jam_masuk) < concat(year(tanggal_periksa),'/01/01'))
                            THEN datediff(date(jam_selesai),concat(year(tanggal_periksa),'/01/01'))+1
                            WHEN 
                                (date(jam_selesai) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_selesai) <= CURDATE()) and 
                                (date(jam_masuk) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_masuk) <= CURDATE())
                            THEN datediff(date(jam_selesai),date(jam_masuk))+1
                            END) AS hp
                        FROM kunjungan where (year(tanggal_periksa)=$thn)
                        and tipe_kunjungan='Rawat Inap' and rl_31=$i
                        GROUP BY year(tanggal_periksa) order by year(tanggal_periksa) desc
                            ";  
                        $hp = Kunjungan::findBySql($SQL)->asArray()->all();
                        $nilai_hp[$i]=0;
                        foreach($hp as $key=>$val):
                            if(isset($val['hp'])){
                                $nilai_hp[$i]=$val['hp'];
                            }
                        endforeach;  
                    }else{
                        $SQL = "SELECT year(tanggal_periksa) as tahun,sum(CASE
                            WHEN 
                                (date('jam_selesai') > concat(year(tanggal_periksa),'12/31') OR date(jam_selesai) is NULL) and 
                                (date('jam_masuk') >= concat(year(tanggal_periksa),'01/01') and 
                                date('jam_masuk') <= concat(year(tanggal_periksa),'12/31'))
                            THEN datediff(concat(year(tanggal_periksa),'/12/31'),date(jam_masuk))+1
                            WHEN 
                                (date(jam_selesai) > concat(year(tanggal_periksa),'/12/31') OR date(jam_selesai) is NULL) and 
                                (date(jam_masuk) < concat(year(tanggal_periksa),'/01/01'))
                            THEN datediff(concat(year(tanggal_periksa),'/12/31'),concat(year(tanggal_periksa),'/01/01'))+1
                            WHEN 
                                (date(jam_selesai) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_selesai) <= concat(year(tanggal_periksa),'/12/31')) and
                                (date(jam_masuk) < concat(year(tanggal_periksa),'/01/01'))
                            THEN datediff(date(jam_selesai),concat(year(tanggal_periksa),'/01/01'))+1
                            WHEN 
                                (date(jam_selesai) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_selesai) <= concat(year(tanggal_periksa),'/12/31')) and 
                                (date(jam_masuk) >= concat(year(tanggal_periksa),'/01/01') and 
                                date(jam_masuk) <= concat(year(tanggal_periksa),'/12/31'))
                            THEN datediff(date(jam_selesai),date(jam_masuk))+1
                            END) AS hp
                        FROM kunjungan where (year(tanggal_periksa)=$thn)
                        and tipe_kunjungan='Rawat Inap' and rl_31=$i
                        GROUP BY year(tanggal_periksa) order by year(tanggal_periksa) desc
                            ";   
                        $hp = Kunjungan::findBySql($SQL)->asArray()->all();
                        $nilai_hp[$i]=0;
                        foreach($hp as $key=>$val):
                            if(isset($val['hp'])){
                                $nilai_hp[$i]=$val['hp'];
                            }
                        endforeach;                        
                    }          

                    //*******************
                    $x=$i;
                    if($x==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_layanan"=>$layanan,
                        "pasien_awal_tahun"=>$nilai_pasien_awal_tahun[$i],
                        "pasien_masuk"=>$nilai_pasien_masuk[$i],
                        "pasien_hidup"=>$nilai_pasien_hidup[$i],
                        "pasien_mati1"=>$nilai_pasien_mati1[$i],
                        "pasien_mati2"=>$nilai_pasien_mati2[$i],
                        "lama_dirawat"=>$nilai_lama_dirawat[$i],
                        "pasien_akhir_tahun"=>$nilai_pasien_akhir_tahun[$i],
                        "jumlah_hp"=>$nilai_hp[$i])
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_layanan"=>$layanan,
                        "pasien_awal_tahun"=>$nilai_pasien_awal_tahun[$i],
                        "pasien_masuk"=>$nilai_pasien_masuk[$i],
                        "pasien_hidup"=>$nilai_pasien_hidup[$i],
                        "pasien_mati1"=>$nilai_pasien_mati1[$i],
                        "pasien_mati2"=>$nilai_pasien_mati2[$i],
                        "lama_dirawat"=>$nilai_lama_dirawat[$i],
                        "pasien_akhir_tahun"=>$nilai_pasien_akhir_tahun[$i],
                        "jumlah_hp"=>$nilai_hp[$i])
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }     
                    //*******************                    
                endforeach;    

                return $this->render($jns,compact('rl','nilai'));                                       
            }

            if($jns=="rl_32"){
                $SQL = "SELECT no, jenis_pelayanan from rl_ref_32 order by no";
                $rl_32 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_32 as $key=>$val):
                    $i=$val['no'];
                    $layanan=$val['jenis_pelayanan'];

                    $SQLpasien_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and
                        a.asal_id <> 1 and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $pasien_rujukan=Kunjungan::findBySql($SQLpasien_rujukan)->asArray()->all();
                    $nilai_pasien_rujukan[$i]=0;
                    foreach($pasien_rujukan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_rujukan[$i]=$val['jml'];
                        }  
                    endforeach;    

                    $SQLpasien_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and
                        a.asal_id = 1 and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $pasien_non_rujukan=Kunjungan::findBySql($SQLpasien_non_rujukan)->asArray()->all();
                    $nilai_pasien_non_rujukan[$i]=0;
                    foreach($pasien_non_rujukan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pasien_non_rujukan[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLdirawat="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and
                        a.parent_id is NOT NULL and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $dirawat=Kunjungan::findBySql($SQLdirawat)->asArray()->all();
                    $nilai_dirawat[$i]=0;
                    foreach($dirawat as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_dirawat[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLdirujuk="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and a.jenis_keluar = 2
                        and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $dirujuk=Kunjungan::findBySql($SQLdirujuk)->asArray()->all();
                    $nilai_dirujuk[$i]=0;
                    foreach($dirujuk as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_dirujuk[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLpulang="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and (a.jenis_keluar = 1 or
                        a.jenis_keluar = 3 or a.jenis_keluar = 5 or a.jenis_keluar = 6)
                        and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $pulang=Kunjungan::findBySql($SQLpulang)->asArray()->all();
                    $nilai_pulang[$i]=0;
                    foreach($pulang as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_pulang[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLmati_igd="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and a.jenis_keluar = 4
                        and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $mati_igd=Kunjungan::findBySql($SQLmati_igd)->asArray()->all();
                    $nilai_mati_igd[$i]=0;
                    foreach($mati_igd as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_mati_igd[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLdoa="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_32 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_32=c.no and a.jenis_keluar = 4
                        and year(date(a.tanggal_periksa))=$thn and b.rl_32=$i ";
                    $doa=Kunjungan::findBySql($SQLdoa)->asArray()->all();
                    $nilai_doa[$i]=0;
                    foreach($doa as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_doa[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_layanan"=>$layanan,
                        "pasien_rujukan"=>$nilai_pasien_rujukan[$i],
                        "pasien_non_rujukan"=>$nilai_pasien_non_rujukan[$i],
                        "dirawat"=>$nilai_dirawat[$i],
                        "dirujuk"=>$nilai_dirujuk[$i],
                        "pulang"=>$nilai_pulang[$i],
                        "mati_igd"=>$nilai_mati_igd[$i],
                        "doa"=>$nilai_doa[$i])
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_layanan"=>$layanan,
                        "pasien_rujukan"=>$nilai_pasien_rujukan[$i],
                        "pasien_non_rujukan"=>$nilai_pasien_non_rujukan[$i],
                        "dirawat"=>$nilai_dirawat[$i],
                        "dirujuk"=>$nilai_dirujuk[$i],
                        "pulang"=>$nilai_pulang[$i],
                        "mati_igd"=>$nilai_mati_igd[$i],
                        "doa"=>$nilai_doa[$i])
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************                            
                endforeach; 

                return $this->render($jns,compact('rl','nilai')); 
            }   
            
            if($jns=="rl_33"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_33 order by no";
                $rl_33 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_33 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan'];

                    $SQLkegiatan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_33 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_33=c.no 
                        and year(date(a.tanggal_periksa))=$thn and b.rl_33=$i ";
                    $jumlah=Kunjungan::findBySql($SQLkegiatan)->asArray()->all();
                    $nilai_jumlah[$i]=0;
                    foreach($jumlah as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_jumlah[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_34"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_34 order by no";
                $rl_34 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_34 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan'];

                    $SQLrumah_sakit="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 3
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $rumah_sakit=Kunjungan::findBySql($SQLrumah_sakit)->asArray()->all();
                    $nilai_rumah_sakit[$i]=0;
                    foreach($rumah_sakit as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rumah_sakit[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLbidan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 4
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $bidan=Kunjungan::findBySql($SQLbidan)->asArray()->all();
                    $nilai_bidan[$i]=0;
                    foreach($bidan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_bidan[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLpuskesmas="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 2
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $puskesmas=Kunjungan::findBySql($SQLpuskesmas)->asArray()->all();
                    $nilai_puskesmas[$i]=0;
                    foreach($puskesmas as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_puskesmas[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLlain_lain="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 2
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $lain_lain=Kunjungan::findBySql($SQLlain_lain)->asArray()->all();
                    $nilai_lain_lain[$i]=0;
                    foreach($lain_lain as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_lain_lain[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLhidup_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and (a.jenis_keluar = 1 
                        or a.jenis_keluar = 3 or a.jenis_keluar = 6) 
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $hidup_medis=Kunjungan::findBySql($SQLhidup_medis)->asArray()->all();
                    $nilai_hidup_medis[$i]=0;
                    foreach($hidup_medis as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_hidup_medis[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLmati_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and (a.jenis_keluar = 4 
                        or a.jenis_keluar = 5) 
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $mati_medis=Kunjungan::findBySql($SQLmati_medis)->asArray()->all();
                    $nilai_mati_medis[$i]=0;
                    foreach($mati_medis as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_mati_medis[$i]=$val['jml'];
                        }  
                    endforeach; 
                    $nilai_total_medis[$i] = $nilai_hidup_medis[$i] +  $nilai_mati_medis[$i];

                    $SQLhidup_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 1 and 
                        (a.jenis_keluar = 1 or a.jenis_keluar = 3 or a.jenis_keluar = 6)
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $hidup_non_rujukan=Kunjungan::findBySql($SQLhidup_non_rujukan)->asArray()->all();
                    $nilai_hidup_non_rujukan[$i]=0;
                    foreach($hidup_non_rujukan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_hidup_non_rujukan[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLmati_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and a.asal_id = 1 and 
                        (a.jenis_keluar = 4 or a.jenis_keluar = 5)
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $mati_non_rujukan=Kunjungan::findBySql($SQLmati_non_rujukan)->asArray()->all();
                    $nilai_mati_non_rujukan[$i]=0;
                    foreach($mati_non_rujukan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_mati_non_rujukan[$i]=$val['jml'];
                        }  
                    endforeach; 
                    $nilai_total_non_rujukan[$i] = $nilai_hidup_non_rujukan[$i] + $nilai_mati_non_rujukan[$i];

                    $SQLdirujuk="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_34 c
                        where a.kunjungan_id=b.kunjungan_id and b.rl_34=c.no and 
                        (a.jenis_keluar = 2)
                        and year(date(a.tanggal_periksa))=$thn and b.rl_34=$i ";
                    $dirujuk=Kunjungan::findBySql($SQLdirujuk)->asArray()->all();
                    $nilai_dirujuk[$i]=0;
                    foreach($dirujuk as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_dirujuk[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rumah_sakit"=>$nilai_rumah_sakit[$i],
                        "bidan"=>$nilai_bidan[$i],
                        "puskesmas"=>$nilai_puskesmas[$i],
                        "lain_lain"=>$nilai_lain_lain[$i],
                        "hidup_medis"=>$nilai_hidup_medis[$i],
                        "mati_medis"=>$nilai_mati_medis[$i],
                        "total_medis"=>$nilai_total_medis[$i],
                        "hidup_non_medis"=>0,
                        "mati_non_medis"=>0,
                        "total_non_medis"=>0,
                        "hidup_non_rujukan"=>$nilai_hidup_non_rujukan[$i],
                        "mati_non_rujukan"=>$nilai_mati_non_rujukan[$i],
                        "total_non_rujukan"=>$nilai_total_non_rujukan[$i],
                        "dirujuk"=>$nilai_dirujuk[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rumah_sakit"=>$nilai_rumah_sakit[$i],
                        "bidan"=>$nilai_bidan[$i],
                        "puskesmas"=>$nilai_puskesmas[$i],
                        "lain_lain"=>$nilai_lain_lain[$i],
                        "hidup_medis"=>$nilai_hidup_medis[$i],
                        "mati_medis"=>$nilai_mati_medis[$i],
                        "total_medis"=>$nilai_total_medis[$i],
                        "hidup_non_medis"=>0,
                        "mati_non_medis"=>0,
                        "total_non_medis"=>0,
                        "hidup_non_rujukan"=>$nilai_hidup_non_rujukan[$i],
                        "mati_non_rujukan"=>$nilai_mati_non_rujukan[$i],
                        "total_non_rujukan"=>$nilai_total_non_rujukan[$i],
                        "dirujuk"=>$nilai_dirujuk[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_35"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_35 order by no asc";
                $rl_35 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_35 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan'];

                    if($i>=1 and $i<2){
                        if($i==1){
                            $nilai_rumah_sakit[$i]='';
                            $nilai_bidan[$i]='';
                            $nilai_puskesmas[$i]='';
                            $nilai_lain_lain[$i]='';
                            $nilai_hidup_medis[$i]='';
                            $nilai_mati_medis[$i]='';
                            $nilai_total_medis[$i]='';
                            $nilai_hidup_non_rujukan[$i]='';
                            $nilai_mati_non_rujukan[$i]='';
                            $nilai_total_non_rujukan[$i]='';
                            $nilai_dirujuk[$i]='';   

                            $nilai_hidup_non_medis[$i]='';
                            $nilai_mati_non_medis[$i]='';
                            $total_non_medis[$i]='';                         
                        }else{
                            $SQLrumah_sakit="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 3
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $rumah_sakit=Kunjungan::findBySql($SQLrumah_sakit)->asArray()->all();
                            $nilai_rumah_sakit[$i]=0;
                            foreach($rumah_sakit as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_rumah_sakit[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLbidan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 4
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $bidan=Kunjungan::findBySql($SQLbidan)->asArray()->all();
                            $nilai_bidan[$i]=0;
                            foreach($bidan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_bidan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLpuskesmas="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $puskesmas=Kunjungan::findBySql($SQLpuskesmas)->asArray()->all();
                            $nilai_puskesmas[$i]=0;
                            foreach($puskesmas as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_puskesmas[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLlain_lain="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $lain_lain=Kunjungan::findBySql($SQLlain_lain)->asArray()->all();
                            $nilai_lain_lain[$i]=0;
                            foreach($lain_lain as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_lain_lain[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLhidup_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and (a.jenis_keluar = 1 
                                or a.jenis_keluar = 3 or a.jenis_keluar = 6) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $hidup_medis=Kunjungan::findBySql($SQLhidup_medis)->asArray()->all();
                            $nilai_hidup_medis[$i]=0;
                            foreach($hidup_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_medis[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and (a.jenis_keluar = 4 
                                or a.jenis_keluar = 5) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $mati_medis=Kunjungan::findBySql($SQLmati_medis)->asArray()->all();
                            $nilai_mati_medis[$i]=0;
                            foreach($mati_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_medis[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_medis[$i] = $nilai_hidup_medis[$i] +  $nilai_mati_medis[$i];

                            $SQLhidup_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 1 or a.jenis_keluar = 3 or a.jenis_keluar = 6)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $hidup_non_rujukan=Kunjungan::findBySql($SQLhidup_non_rujukan)->asArray()->all();
                            $nilai_hidup_non_rujukan[$i]=0;
                            foreach($hidup_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 4 or a.jenis_keluar = 5)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $mati_non_rujukan=Kunjungan::findBySql($SQLmati_non_rujukan)->asArray()->all();
                            $nilai_mati_non_rujukan[$i]=0;
                            foreach($mati_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_non_rujukan[$i] = $nilai_hidup_non_rujukan[$i] + $nilai_mati_non_rujukan[$i];

                            $SQLdirujuk="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_1=c.no and 
                                (a.jenis_keluar = 2)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_1=$i ";
                            $dirujuk=Kunjungan::findBySql($SQLdirujuk)->asArray()->all();
                            $nilai_dirujuk[$i]=0;
                            foreach($dirujuk as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_dirujuk[$i]=$val['jml'];
                                }  
                            endforeach;

                            $nilai_hidup_non_medis[$i]=0;
                            $nilai_mati_non_medis[$i]=0;
                            $total_non_medis[$i]=0; 
                        } 
                    }elseif ($i>=2 and $i<3) {
                        if($i==2){
                            $nilai_rumah_sakit[$i]='';
                            $nilai_bidan[$i]='';
                            $nilai_puskesmas[$i]='';
                            $nilai_lain_lain[$i]='';
                            $nilai_hidup_medis[$i]='';
                            $nilai_mati_medis[$i]='';
                            $nilai_total_medis[$i]='';
                            $nilai_hidup_non_rujukan[$i]='';
                            $nilai_mati_non_rujukan[$i]='';
                            $nilai_total_non_rujukan[$i]='';
                            $nilai_dirujuk[$i]=''; 

                            $nilai_hidup_non_medis[$i]='';
                            $nilai_mati_non_medis[$i]='';
                            $total_non_medis[$i]=''; 
                        }else{
                            $SQLrumah_sakit="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 3
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $rumah_sakit=Kunjungan::findBySql($SQLrumah_sakit)->asArray()->all();
                            $nilai_rumah_sakit[$i]=0;
                            foreach($rumah_sakit as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_rumah_sakit[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLbidan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 4
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $bidan=Kunjungan::findBySql($SQLbidan)->asArray()->all();
                            $nilai_bidan[$i]=0;
                            foreach($bidan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_bidan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLpuskesmas="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $puskesmas=Kunjungan::findBySql($SQLpuskesmas)->asArray()->all();
                            $nilai_puskesmas[$i]=0;
                            foreach($puskesmas as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_puskesmas[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLlain_lain="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $lain_lain=Kunjungan::findBySql($SQLlain_lain)->asArray()->all();
                            $nilai_lain_lain[$i]=0;
                            foreach($lain_lain as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_lain_lain[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLhidup_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and (a.jenis_keluar = 1 
                                or a.jenis_keluar = 3 or a.jenis_keluar = 6) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $hidup_medis=Kunjungan::findBySql($SQLhidup_medis)->asArray()->all();
                            $nilai_hidup_medis[$i]=0;
                            foreach($hidup_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_medis[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and (a.jenis_keluar = 4 
                                or a.jenis_keluar = 5) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $mati_medis=Kunjungan::findBySql($SQLmati_medis)->asArray()->all();
                            $nilai_mati_medis[$i]=0;
                            foreach($mati_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_medis[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_medis[$i] = $nilai_hidup_medis[$i] +  $nilai_mati_medis[$i];

                            $SQLhidup_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 1 or a.jenis_keluar = 3 or a.jenis_keluar = 6)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $hidup_non_rujukan=Kunjungan::findBySql($SQLhidup_non_rujukan)->asArray()->all();
                            $nilai_hidup_non_rujukan[$i]=0;
                            foreach($hidup_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 4 or a.jenis_keluar = 5)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $mati_non_rujukan=Kunjungan::findBySql($SQLmati_non_rujukan)->asArray()->all();
                            $nilai_mati_non_rujukan[$i]=0;
                            foreach($mati_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_non_rujukan[$i] = $nilai_hidup_non_rujukan[$i] + $nilai_mati_non_rujukan[$i];

                            $SQLdirujuk="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_2=c.no and 
                                (a.jenis_keluar = 2)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_2=$i ";
                            $dirujuk=Kunjungan::findBySql($SQLdirujuk)->asArray()->all();
                            $nilai_dirujuk[$i]=0;
                            foreach($dirujuk as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_dirujuk[$i]=$val['jml'];
                                }  
                            endforeach;

                            $nilai_hidup_non_medis[$i]=0;
                            $nilai_mati_non_medis[$i]=0;
                            $total_non_medis[$i]=0; 
                        }                         
                    }elseif ($i>=3) {
                        if($i==3){
                            $nilai_rumah_sakit[$i]='';
                            $nilai_bidan[$i]='';
                            $nilai_puskesmas[$i]='';
                            $nilai_lain_lain[$i]='';
                            $nilai_hidup_medis[$i]='';
                            $nilai_mati_medis[$i]='';
                            $nilai_total_medis[$i]='';
                            $nilai_hidup_non_rujukan[$i]='';
                            $nilai_mati_non_rujukan[$i]='';
                            $nilai_total_non_rujukan[$i]='';
                            $nilai_dirujuk[$i]=''; 

                            $nilai_hidup_non_medis[$i]='';
                            $nilai_mati_non_medis[$i]='';
                            $total_non_medis[$i]='';                             
                        }else{
                            $SQLrumah_sakit="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 3
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $rumah_sakit=Kunjungan::findBySql($SQLrumah_sakit)->asArray()->all();
                            $nilai_rumah_sakit[$i]=0;
                            foreach($rumah_sakit as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_rumah_sakit[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLbidan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 4
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $bidan=Kunjungan::findBySql($SQLbidan)->asArray()->all();
                            $nilai_bidan[$i]=0;
                            foreach($bidan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_bidan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLpuskesmas="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $puskesmas=Kunjungan::findBySql($SQLpuskesmas)->asArray()->all();
                            $nilai_puskesmas[$i]=0;
                            foreach($puskesmas as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_puskesmas[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLlain_lain="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 2
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $lain_lain=Kunjungan::findBySql($SQLlain_lain)->asArray()->all();
                            $nilai_lain_lain[$i]=0;
                            foreach($lain_lain as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_lain_lain[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLhidup_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and (a.jenis_keluar = 1 
                                or a.jenis_keluar = 3 or a.jenis_keluar = 6) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $hidup_medis=Kunjungan::findBySql($SQLhidup_medis)->asArray()->all();
                            $nilai_hidup_medis[$i]=0;
                            foreach($hidup_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_medis[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_medis="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and (a.jenis_keluar = 4 
                                or a.jenis_keluar = 5) 
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $mati_medis=Kunjungan::findBySql($SQLmati_medis)->asArray()->all();
                            $nilai_mati_medis[$i]=0;
                            foreach($mati_medis as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_medis[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_medis[$i] = $nilai_hidup_medis[$i] +  $nilai_mati_medis[$i];

                            $SQLhidup_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 1 or a.jenis_keluar = 3 or a.jenis_keluar = 6)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $hidup_non_rujukan=Kunjungan::findBySql($SQLhidup_non_rujukan)->asArray()->all();
                            $nilai_hidup_non_rujukan[$i]=0;
                            foreach($hidup_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_hidup_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 

                            $SQLmati_non_rujukan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and a.asal_id = 1 and 
                                (a.jenis_keluar = 4 or a.jenis_keluar = 5)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $mati_non_rujukan=Kunjungan::findBySql($SQLmati_non_rujukan)->asArray()->all();
                            $nilai_mati_non_rujukan[$i]=0;
                            foreach($mati_non_rujukan as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_mati_non_rujukan[$i]=$val['jml'];
                                }  
                            endforeach; 
                            $nilai_total_non_rujukan[$i] = $nilai_hidup_non_rujukan[$i] + $nilai_mati_non_rujukan[$i];

                            $SQLdirujuk="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_35 c
                                where a.kunjungan_id=b.kunjungan_id and b.rl_35_3=c.no and 
                                (a.jenis_keluar = 2)
                                and year(date(a.tanggal_periksa))=$thn and b.rl_35_3=$i ";
                            $dirujuk=Kunjungan::findBySql($SQLdirujuk)->asArray()->all();
                            $nilai_dirujuk[$i]=0;
                            foreach($dirujuk as $key=>$val):
                                if(isset($val['jml'])){
                                    $nilai_dirujuk[$i]=$val['jml'];
                                }  
                            endforeach;   

                            $nilai_hidup_non_medis[$i]=0;
                            $nilai_mati_non_medis[$i]=0;
                            $total_non_medis[$i]=0;                          
                        }
                           
                    }

                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rumah_sakit"=>$nilai_rumah_sakit[$i],
                        "bidan"=>$nilai_bidan[$i],
                        "puskesmas"=>$nilai_puskesmas[$i],
                        "lain_lain"=>$nilai_lain_lain[$i],
                        "hidup_medis"=>$nilai_hidup_medis[$i],
                        "mati_medis"=>$nilai_mati_medis[$i],
                        "total_medis"=>$nilai_total_medis[$i],
                        "hidup_non_medis"=>$nilai_hidup_non_medis[$i],
                        "mati_non_medis"=>$nilai_mati_non_medis[$i],
                        "total_non_medis"=>$total_non_medis[$i],
                        "hidup_non_rujukan"=>$nilai_hidup_non_rujukan[$i],
                        "mati_non_rujukan"=>$nilai_mati_non_rujukan[$i],
                        "total_non_rujukan"=>$nilai_total_non_rujukan[$i],
                        "dirujuk"=>$nilai_dirujuk[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rumah_sakit"=>$nilai_rumah_sakit[$i],
                        "bidan"=>$nilai_bidan[$i],
                        "puskesmas"=>$nilai_puskesmas[$i],
                        "lain_lain"=>$nilai_lain_lain[$i],
                        "hidup_medis"=>$nilai_hidup_medis[$i],
                        "mati_medis"=>$nilai_mati_medis[$i],
                        "total_medis"=>$nilai_total_medis[$i],
                        "hidup_non_medis"=>$nilai_hidup_non_medis[$i],
                        "mati_non_medis"=>$nilai_mati_non_medis[$i],
                        "total_non_medis"=>$total_non_medis[$i],
                        "hidup_non_rujukan"=>$nilai_hidup_non_rujukan[$i],
                        "mati_non_rujukan"=>$nilai_mati_non_rujukan[$i],
                        "total_non_rujukan"=>$nilai_total_non_rujukan[$i],
                        "dirujuk"=>$nilai_dirujuk[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_36"){
                $SQL = "SELECT no, spesialisasi from rl_ref_36 order by no";
                $rl_36 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_36 as $key=>$val):
                    $i=$val['no'];
                    $spesialisasi=$val['spesialisasi'];

                    $SQLrk_3="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_36 e, rl_ref_36 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.tindakan_cd=e.tindakan_cd and e.rl_ref_36_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' ";
                    $rk_3=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_3[$i]=0;
                    foreach($rk_3 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_3[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_4="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_36 e, rl_ref_36 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.tindakan_cd=e.tindakan_cd and e.rl_ref_36_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' and e.jenis='Khusus' ";
                    $rk_4=Kunjungan::findBySql($SQLrk_4)->asArray()->all();
                    $nilai_rk_4[$i]=0;
                    foreach($rk_4 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_4[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_5="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_36 e, rl_ref_36 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.tindakan_cd=e.tindakan_cd and e.rl_ref_36_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' and e.jenis='Besar' ";
                    $rk_5=Kunjungan::findBySql($SQLrk_5)->asArray()->all();
                    $nilai_rk_5[$i]=0;
                    foreach($rk_5 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_5[$i]=$val['jml'];
                        }  
                    endforeach;                     

                    $SQLrk_6="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_36 e, rl_ref_36 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.tindakan_cd=e.tindakan_cd and e.rl_ref_36_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' and e.jenis='Sedang' ";
                    $rk_6=Kunjungan::findBySql($SQLrk_6)->asArray()->all();
                    $nilai_rk_6[$i]=0;
                    foreach($rk_6 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_6[$i]=$val['jml'];
                        }  
                    endforeach;  

                    $SQLrk_7="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_36 e, rl_ref_36 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.tindakan_cd=e.tindakan_cd and e.rl_ref_36_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' and e.jenis='Kecil' ";
                    $rk_7=Kunjungan::findBySql($SQLrk_7)->asArray()->all();
                    $nilai_rk_7[$i]=0;
                    foreach($rk_7 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_7[$i]=$val['jml'];
                        }  
                    endforeach;  
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "spesialisasi"=>$spesialisasi,
                        "total"=>$nilai_rk_3[$i],
                        "khusus"=>$nilai_rk_4[$i],
                        "besar"=>$nilai_rk_5[$i],
                        "sedang"=>$nilai_rk_6[$i],
                        "kecil"=>$nilai_rk_7[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "spesialisasi"=>$spesialisasi,
                        "total"=>$nilai_rk_3[$i],
                        "khusus"=>$nilai_rk_4[$i],
                        "besar"=>$nilai_rk_5[$i],
                        "sedang"=>$nilai_rk_6[$i],
                        "kecil"=>$nilai_rk_7[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_37"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_37 order by no";
                $rl_37 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_37 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan'];

                    $SQLkegiatan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_37 e, rl_ref_37 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.klinik_id=e.medicalunit_cd and e.rl_ref_37_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' ";
                    $jenis_kegiatan=Kunjungan::findBySql($SQLkegiatan)->asArray()->all();
                    $nilai_kegiatan[$i]=0;
                    foreach($jenis_kegiatan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_kegiatan[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_kegiatan[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_kegiatan[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_38"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_38 order by no";
                $rl_38 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_38 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan'];

                    $SQLkegiatan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_tindakan c, tindakan d, 
                        rl_grouping_38 e, rl_ref_38 f 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.tindakan_cd=d.tindakan_cd and 
                        d.klinik_id=e.medicalunit_cd and e.rl_ref_38_no=f.no and year(date(a.tanggal_periksa))=$thn and 
                        f.no='$i' ";
                    $jenis_kegiatan=Kunjungan::findBySql($SQLkegiatan)->asArray()->all();
                    $nilai_kegiatan[$i]=0;
                    foreach($jenis_kegiatan as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_kegiatan[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_kegiatan[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_kegiatan[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_39"){

            } 

            if($jns=="rl_310"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_310 order by no asc ";
                $rl_310 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_310 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_kegiatan']; 

                    $SQLrk_3=" SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_diagnosis c, rl_ref_310 d 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.no and 
                        year(date(tanggal_periksa))=$thn and d.no='$i' ";
                    $rk_3=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_3[$i]=0;
                    foreach($rk_3 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_3[$i]=$val['jml'];
                        }  
                    endforeach; 

                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rk_3"=>$nilai_rk_3[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rk_3"=>$nilai_rk_3[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************  
                endforeach; 

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_311"){
                $SQL = "SELECT no, jenis_pelayanan from rl_ref_311 order by no asc ";
                $rl_311 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_311 as $key=>$val):
                    $i=$val['no'];
                    $kegiatan=$val['jenis_pelayanan']; 

                    $SQLrk_3=" SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_diagnosis c, rl_ref_311 d 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.no and 
                        year(date(tanggal_periksa))=$thn and d.no='$i' ";
                    $rk_3=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_3[$i]=0;
                    foreach($rk_3 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_3[$i]=$val['jml'];
                        }  
                    endforeach; 

                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rk_3"=>$nilai_rk_3[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "rk_3"=>$nilai_rk_3[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************  
                endforeach; 

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_312"){

            } 

            if($jns=="rl_313"){

            } 

            if($jns=="rl_314"){
                $SQL = "SELECT no, jenis_spesialisasi from rl_ref_314 order by no asc ";
                $rl_314 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_314 as $key=>$val):
                    $i=$val['no'];
                    $spesialisasi=$val['jenis_spesialisasi']; 

                    $SQLrk_3=" SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_diagnosis c, rl_ref_314 d 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.no and 
                        year(date(tanggal_periksa))=$thn and d.no='$i' and a.asal_id=2 ";
                    $rk_3=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_3[$i]=0;
                    foreach($rk_3 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_3[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_4=" SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_diagnosis c, rl_ref_314 d 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.no and 
                        year(date(tanggal_periksa))=$thn and d.no='$i' and (a.asal_id >= 4 )";
                    $rk_4=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_4[$i]=0;
                    foreach($rk_4 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_4[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_5=" SELECT count(*) as jml from kunjungan a, rekam_medis b, rm_diagnosis c, rl_ref_314 d 
                        where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.no and 
                        year(date(tanggal_periksa))=$thn and d.no='$i' and (a.asal_id = 3 )";
                    $rk_5=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_5[$i]=0;
                    foreach($rk_5 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_5[$i]=$val['jml'];
                        }  
                    endforeach; 
                    $nilai_rk_6[$i]='';
                    $nilai_rk_7[$i]='';
                    $nilai_rk_8[$i]='';
                    $nilai_rk_9[$i]='';
                    $nilai_rk_10[$i]='';
                    $nilai_rk_11[$i]='';
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_spesialisasi"=>$spesialisasi,
                        "rk_3"=>$nilai_rk_3[$i],
                        "rk_4"=>$nilai_rk_4[$i],
                        "rk_5"=>$nilai_rk_5[$i],
                        "rk_6"=>$nilai_rk_6[$i],
                        "rk_7"=>$nilai_rk_7[$i],
                        "rk_8"=>$nilai_rk_8[$i],
                        "rk_9"=>$nilai_rk_9[$i],
                        "rk_10"=>$nilai_rk_10[$i],
                        "rk_11"=>$nilai_rk_11[$i]
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_spesialisasi"=>$spesialisasi,
                        "rk_3"=>$nilai_rk_3[$i],
                        "rk_4"=>$nilai_rk_4[$i],
                        "rk_5"=>$nilai_rk_5[$i],
                        "rk_6"=>$nilai_rk_6[$i],
                        "rk_7"=>$nilai_rk_7[$i],
                        "rk_8"=>$nilai_rk_8[$i],
                        "rk_9"=>$nilai_rk_9[$i],
                        "rk_10"=>$nilai_rk_10[$i],
                        "rk_11"=>$nilai_rk_11[$i]
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************  
                endforeach; 

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_315"){
                $SQL = "SELECT cara_id, cara_nama, cara_tipe from cara_bayar order by cara_id asc ";
                $rl_315 = Kunjungan::findBySql($SQL)->asArray()->all();
                foreach($rl_315 as $key=>$val):
                    $i=$val['cara_id'];
                    $nama=$val['cara_nama'];

                    $SQLrk_3="SELECT count(*) as jml from kunjungan  
                        where tipe_kunjungan='Rawat Inap' 
                        and year(date(jam_selesai))=$thn and cara_id='$i' ";
                    $rk_3=Kunjungan::findBySql($SQLrk_3)->asArray()->all();
                    $nilai_rk_3[$i]=0;
                    foreach($rk_3 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_3[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_4="SELECT sum(datediff(date(jam_selesai),date(jam_masuk))+1) as jml from kunjungan  
                        where tipe_kunjungan='Rawat Inap' 
                        and year(date(jam_selesai))=$thn and cara_id='$i' ";
                    $rk_4=Kunjungan::findBySql($SQLrk_4)->asArray()->all();
                    $nilai_rk_4[$i]=0;
                    foreach($rk_4 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_4[$i]=$val['jml'];
                        }  
                    endforeach; 

                    $SQLrk_5="SELECT count(*) as jml from kunjungan  
                        where tipe_kunjungan='Rawat Jalan' 
                        and year(date(jam_selesai))=$thn and cara_id='$i' ";
                    $rk_5=Kunjungan::findBySql($SQLrk_5)->asArray()->all();
                    $nilai_rk_5[$i]=0;
                    foreach($rk_5 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_5[$i]=$val['jml'];
                        }  
                    endforeach;

                    $SQLrk_6="SELECT count(*) as jml from kunjungan  
                        where tipe_kunjungan='Rawat Jalan' 
                        and year(date(jam_selesai))=$thn and cara_id='$i' ";
                    $rk_6=Kunjungan::findBySql($SQLrk_6)->asArray()->all();
                    $nilai_rk_6[$i]=0;
                    foreach($rk_6 as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_rk_6[$i]=$val['jml'];
                        }  
                    endforeach;
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "cara_bayar"=>$nama,
                        "rk_3"=>$nilai_rk_3[$i],
                        "rk_4"=>$nilai_rk_4[$i],
                        "rk_5"=>$nilai_rk_5[$i],
                        "rk_6"=>0,
                        "rk_7"=>0,
                        "rk_8"=>0
                        )
                    );                            
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "cara_bayar"=>$nama,
                        "rk_3"=>$nilai_rk_3[$i],
                        "rk_4"=>$nilai_rk_4[$i],
                        "rk_5"=>$nilai_rk_5[$i],
                        "rk_6"=>0,
                        "rk_7"=>0,
                        "rk_8"=>0
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);  
                    }    
                //*******************   
                endforeach; 
                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_4a"){
                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_4b"){
                return $this->render($jns,compact('rl','nilai'));
            } 

            if($jns=="rl_51"){
                $SQL = "SELECT distinct baru_lama from kunjungan order by baru_lama asc ";
                $baru_lama = Kunjungan::findBySql($SQL)->asArray()->all();
                $i=1;
                foreach($baru_lama as $key=>$val):
                    $kegiatan=$val['baru_lama'];

                    $SQLkegiatan="SELECT count(*) as jml from kunjungan 
                        where year(date(tanggal_periksa))=$thn and baru_lama=$i ";
                    $jumlah=Kunjungan::findBySql($SQLkegiatan)->asArray()->all();
                    $nilai_jumlah[$i]=0;
                    foreach($jumlah as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_jumlah[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );                           
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );        
                    $nilai=array_merge($nilai, $nilai2);    
                    }    
                //*******************   
                    $i++;
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_52"){
                $SQL = "SELECT no, jenis_kegiatan from rl_ref_52 order by no asc ";
                $rl_52 = Kunjungan::findBySql($SQL)->asArray()->all();
                $i=1;
                foreach($rl_52 as $key=>$val):
                    $kegiatan=$val['jenis_kegiatan'];

                    $SQLkegiatan="SELECT count(*) as jml from kunjungan a, rekam_medis b, rl_ref_52 c, rm_diagnosis d 
                        where year(date(a.tanggal_periksa))=$thn and a.kunjungan_id=b.kunjungan_id and
                        b.rm_id=d.rm_id and d.kode=c.no and c.no='$i' ";
                    $jumlah=Kunjungan::findBySql($SQLkegiatan)->asArray()->all();
                    $nilai_jumlah[$i]=0;
                    foreach($jumlah as $key=>$val):
                        if(isset($val['jml'])){
                            $nilai_jumlah[$i]=$val['jml'];
                        }  
                    endforeach; 
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );                           
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "jenis_kegiatan"=>$kegiatan,
                        "jumlah"=>$nilai_jumlah[$i])
                    );        
                    $nilai=array_merge($nilai, $nilai2);    
                    }    
                //*******************   
                    $i++;
                endforeach;

                return $this->render($jns,compact('rl','nilai')); 
            } 

            if($jns=="rl_53"){
                $SQL = "SELECT d.id as kode, d.short_desc as deskripsi, count(c.kode) as jml 
                from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d 
                where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                a.tipe_kunjungan='Rawat Inap' and year(date(a.jam_selesai))=$thn 
                group by d.id order by jml desc ";
                $rl_53 = Kunjungan::findBySql($SQL)->asArray()->all();
                $i=1;
                foreach($rl_53 as $key=>$val):
                    $kode=$val['kode'];
                    $deskripsi=$val['deskripsi'];
                    $total=$val['jml'];

                    $SQL_4="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Inap' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Laki-Laki' and c.kode='$kode' and (a.jenis_keluar<>4 or a.jenis_keluar<>5) ";
                    $rk_4 = Kunjungan::findBySql($SQL_4)->asArray()->all();
                    $nilai_4[$kode]=0;
                    foreach($rk_4 as $key=>$val):
                        $nilai_4[$kode]=$val['jml'];
                    endforeach;

                    $SQL_5="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Inap' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Perempuan' and c.kode='$kode' and (a.jenis_keluar<>4 or a.jenis_keluar<>5) ";
                    $rk_5 = Kunjungan::findBySql($SQL_4)->asArray()->all();
                    $nilai_5[$kode]=0;
                    foreach($rk_5 as $key=>$val):
                        $nilai_5[$kode]=$val['jml'];
                    endforeach;

                    $SQL_6="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Inap' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Laki-Laki' and c.kode='$kode' and (a.jenis_keluar=4 or a.jenis_keluar=5) ";
                    $rk_6 = Kunjungan::findBySql($SQL_6)->asArray()->all();
                    $nilai_6[$kode]=0;
                    foreach($rk_6 as $key=>$val):
                        $nilai_6[$kode]=$val['jml'];
                    endforeach;

                    $SQL_7="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Inap' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Perempuan' and c.kode='$kode' and (a.jenis_keluar=4 or a.jenis_keluar=5) ";
                    $rk_7 = Kunjungan::findBySql($SQL_7)->asArray()->all();
                    $nilai_7[$kode]=0;
                    foreach($rk_7 as $key=>$val):
                        $nilai_7[$kode]=$val['jml'];
                    endforeach;
                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "kode"=>$kode,
                        "deskripsi"=>$deskripsi,
                        "nilai_4"=>$nilai_4[$kode],
                        "nilai_5"=>$nilai_5[$kode],
                        "nilai_6"=>$nilai_6[$kode],
                        "nilai_7"=>$nilai_7[$kode],
                        "nilai_8"=>$total
                        )
                    );                           
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "kode"=>$kode,
                        "deskripsi"=>$deskripsi,
                        "nilai_4"=>$nilai_4[$kode],
                        "nilai_5"=>$nilai_5[$kode],
                        "nilai_6"=>$nilai_6[$kode],
                        "nilai_7"=>$nilai_7[$kode],
                        "nilai_8"=>$total
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);    
                    }    
                //*******************  
                    $i++;
                endforeach;

                if(isset($nilai)){
                    return $this->render($jns,compact('rl','nilai')); 
                }else{
                    $nilai=array(
                     array("no"=>'',
                        "kode"=>'',
                        "deskripsi"=>'',
                        "nilai_4"=>'',
                        "nilai_5"=>'',
                        "nilai_6"=>'',
                        "nilai_7"=>'',
                        "nilai_8"=>''
                        )
                    );       
                    return $this->render($jns,compact('rl','nilai'));              
                }                
            } 

            if($jns=="rl_54"){
                $SQL = "SELECT d.id as kode, d.short_desc as deskripsi, count(c.kode) as jml 
                from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d 
                where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                a.tipe_kunjungan='Rawat Jalan' and year(date(a.jam_selesai))=$thn 
                group by d.id order by jml desc ";
                $rl_54 = Kunjungan::findBySql($SQL)->asArray()->all();
                $i=1;
                foreach($rl_54 as $key=>$val):
                    $kode=$val['kode'];
                    $deskripsi=$val['deskripsi'];
                    $total=$val['jml'];

                    $SQL_4="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Jalan' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Laki-Laki' and c.kode='$kode' and c.kasus='Baru' ";
                    $rk_4 = Kunjungan::findBySql($SQL_4)->asArray()->all();
                    $nilai_4[$kode]=0;
                    foreach($rk_4 as $key=>$val):
                        $nilai_4[$kode]=$val['jml'];
                    endforeach;

                    $SQL_5="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d, pasien e 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Jalan' and year(date(a.jam_selesai))=$thn and 
                    a.mr=e.mr and e.jk='Perempuan' and c.kode='$kode' and c.kasus='Baru' ";
                    $rk_5 = Kunjungan::findBySql($SQL_4)->asArray()->all();
                    $nilai_5[$kode]=0;
                    foreach($rk_5 as $key=>$val):
                        $nilai_5[$kode]=$val['jml'];
                    endforeach;

                    $SQL_6="SELECT count(*) as jml 
                    from kunjungan a, rekam_medis b, rm_diagnosis c, eklaim_icd9cm d 
                    where a.kunjungan_id=b.kunjungan_id and b.rm_id=c.rm_id and c.kode=d.id and 
                    a.tipe_kunjungan='Rawat Jalan' and year(date(a.jam_selesai))=$thn and 
                    and c.kode='$kode' and c.kasus='Baru' ";
                    $rk_6 = Kunjungan::findBySql($SQL_4)->asArray()->all();
                    $nilai_6[$kode]=0;
                    foreach($rk_6 as $key=>$val):
                        $nilai_6[$kode]=$val['jml'];
                    endforeach;

                //*******************
                    if($i==1){
                    $nilai=array(
                     array("no"=>$i,
                        "kode"=>$kode,
                        "deskripsi"=>$deskripsi,
                        "nilai_4"=>$nilai_4[$kode],
                        "nilai_5"=>$nilai_5[$kode],
                        "nilai_6"=>$nilai_6[$kode],
                        "nilai_7"=>$total
                        )
                    );                           
                    }else{
                    $nilai2=array(
                     array("no"=>$i,
                        "kode"=>$kode,
                        "deskripsi"=>$deskripsi,
                        "nilai_4"=>$nilai_4[$kode],
                        "nilai_5"=>$nilai_5[$kode],
                        "nilai_6"=>$nilai_6[$kode],
                        "nilai_7"=>$total
                        )
                    );        
                    $nilai=array_merge($nilai, $nilai2);    
                    }    
                //*******************  
                    $i++;
                endforeach;

                if(isset($nilai)){
                    return $this->render($jns,compact('rl','nilai')); 
                }else{
                    $nilai=array(
                     array("no"=>'',
                        "kode"=>'',
                        "deskripsi"=>'',
                        "nilai_4"=>'',
                        "nilai_5"=>'',
                        "nilai_6"=>'',
                        "nilai_7"=>'',
                        )
                    );       
                    return $this->render($jns,compact('rl','nilai'));              
                }  
            } 

            $data = $rl->getHasilLaporan(
                $post_data['jenis_laporan'],$post_data['start_date'],$post_data['end_date']
            );

            $title_arr = [
                "rl_12" => "RL 1.2 - Indikator Pelayanan",
                "rl_13" => "RL 1.3 - Jumlah Tempat Tidur",
            ];

            $title = $title_arr[$post_data['jenis_laporan']];
            
            return $this->render('rl_general',compact('data','post_data','title'));
        }

        return $this->render('index',compact('jenis_laporan'));
    }
}