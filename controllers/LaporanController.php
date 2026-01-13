<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Klinik;
use app\models\ContactForm;
use app\models\Bayar;
use app\models\Dokter;
use app\models\RekamMedis;
use app\models\Kunjungan;
use app\models\ForgotForm;
use app\models\ResetForm;
use app\models\User;
use app\models\Rl;
use app\models\Keuangan;
use app\models\UserToken;
use kartik\mpdf\Pdf;
use app\models\Laporan;
use app\models\Ruang;
use yii\db\mssql\PDO;


class LaporanController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','logout'],
                'rules' => [
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        $kunjungan = new Kunjungan();
        $laporan = new Laporan();
        $alasan_kunjungan_tahun_ini = $this->getAlasanKunjungan(date('Y').'-01-01',date('Y').'-12-31');
        $tipe_kunjungan_tahun_ini = $this->getTipeKunjungan(date('Y').'-01-01',date('Y').'-12-31');
        $unit_medis = $this->getUnitMedis();
        $ruang_ranap = $this->getRuangRanap();
        $cara_bayar = $this->getCaraBayar(date('Y'));

        return $this->render('index',compact('kunjungan','laporan','alasan_kunjungan_tahun_ini','tipe_kunjungan_tahun_ini','unit_medis','ruang_ranap','cara_bayar'));
    }

    public function actionRl()
    {
        $model = new Rl();
        if(!empty(Yii::$app->request->post())){
            $post_data = Yii::$app->request->post();
            $data = $model->getHasilLaporan(
                $post_data['jenis_laporan'],$post_data['start_date'],$post_data['end_date']
            );
            if($post_data['jenis_laporan']=='rl_34'){
                $shaped_data = $this->shapeRL34($data);
                return $this->render('rl_34',compact('data','shaped_data'));
            }
            return $this->render($post_data['jenis_laporan'],['data'=>$data]);
        }
        return $this->render('rl',[
            'jenis_laporan' => $model->jenis_laporan
        ]);
    }

    public function actionKeuangan()
    {
        $model = new Keuangan();
        if(!empty(Yii::$app->request->post())){
            $post_data = Yii::$app->request->post();
            $data = $model->getHasilLaporan(
                $post_data['jenis_laporan'],$post_data['start_date'],$post_data['end_date']
            );
            
            return $this->render($post_data['jenis_laporan'],compact('data','post_data'));
        }
        return $this->render('keuangan',[
            'jenis_laporan' => $model->jenis_laporan
        ]);
    }

    public function actionSensusRajal()
    {
        $post_data = Yii::$app->request->post();

        if(!empty($post_data)){
            $connection = yii::$app->db;
            $tgl_awal = $post_data['tgl_awal'];
            $tgl_akhir = $post_data['tgl_akhir'];
            $sql = "SELECT 
                      kunjungan.`tanggal_periksa` AS `Tanggal Periksa`,
                      kunjungan.`jam_masuk` AS `Jam Masuk`,
                      kunjungan.`jam_selesai` AS `Jam Selesai`,
                      dokter.`nama` AS `Dokter`,
                      unit_medis.`medunit_nm` AS `Poli`,
                      kunjungan.`baru_lama` AS `Tipe Kunjungan`,
                      cara_bayar.`cara_nama` AS `Cara Bayar`,
                      kunjungan.`jenis_keluar`,
                      kunjungan.`catatan_keluar` AS `Catatan Keluar`,
                      asuransi.`insurance_nm` AS `Asuransi`,
                      jenis_kunjungan.`jns_kunjungan_nama` AS `Jenis Kunjungan`,
                      region.`region_nm` AS `Asal`,
                      pasien.* 
                    FROM
                      kunjungan 
                      JOIN rekam_medis USING (kunjungan_id) 
                      JOIN pasien 
                        ON rekam_medis.`mr` = pasien.`mr` 
                      JOIN dokter 
                        ON kunjungan.`dpjp` = dokter.`user_id` 
                      JOIN unit_medis USING (medunit_cd) 
                      LEFT JOIN cara_bayar USING (cara_id) 
                      LEFT JOIN asuransi USING (insurance_cd) 
                      LEFT JOIN jenis_kunjungan USING (jns_kunjungan_id) 
                      LEFT JOIN region USING (region_cd)
                    WHERE kunjungan.`tipe_kunjungan` = 'Rawat Jalan' 
                      AND kunjungan.`tanggal_periksa` BETWEEN '$tgl_awal' 
                      AND '$tgl_akhir' ;

                    ";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            // echo '<pre>';print_r($data);exit;
            $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'Sensus Rajal' => [
                        'data' => $data,
                        'titles' => array_keys($data[0])
                    ]
                ]
            ]);
            $file->send("Sensus Rawat Jalan $tgl_awal $tgl_akhir.xlsx"); 
        }

        return $this->render('sensus_rajal');
    }

    public function actionSensusRanap()
    {
        $post_data = Yii::$app->request->post();

        if(!empty($post_data)){
            $connection = yii::$app->db;
            $tgl_awal = $post_data['tgl_awal'];
            $tgl_akhir = $post_data['tgl_akhir'];
            $sql = "

            SELECT 
              kunjungan.`tanggal_periksa` AS `Tanggal Periksa`,
              kunjungan.`jam_masuk` AS `Jam Masuk`,
              kunjungan.`jam_selesai` AS `Jam Selesai`,
              dokter.`nama` AS `Dokter`,
              bangsal.`bangsal_nm` AS `Bangsal`,
              ruang.`ruang_nm` AS `Ruang`,
              kelas.`kelas_nm` AS `Kelas`,
              kunjungan.`baru_lama` AS `Tipe Kunjungan`,
              cara_bayar.`cara_nama` AS `Cara Bayar`,
              kunjungan.`jenis_keluar`,
              kunjungan.`catatan_keluar` AS `Catatan Keluar`,
              asuransi.`insurance_nm` AS `Asuransi`,
              jenis_kunjungan.`jns_kunjungan_nama` AS `Jenis Kunjungan`,
              region.`region_nm` AS `Asal`,
              pasien.* 
            FROM
              kunjungan 
              JOIN rekam_medis USING (kunjungan_id) 
              JOIN pasien 
                ON rekam_medis.`mr` = pasien.`mr` 
              JOIN dokter 
                ON kunjungan.`dpjp` = dokter.`user_id` 
              LEFT JOIN ruang USING (ruang_cd) 
              LEFT JOIN bangsal USING (bangsal_cd)
              LEFT JOIN kelas ON kelas.`kelas_cd` = ruang.`kelas_cd`
              LEFT JOIN cara_bayar USING (cara_id) 
              LEFT JOIN asuransi USING (insurance_cd) 
              LEFT JOIN jenis_kunjungan USING (jns_kunjungan_id) 
              LEFT JOIN region USING (region_cd)
            WHERE kunjungan.`tipe_kunjungan` = 'Rawat Inap' 
              AND kunjungan.`tanggal_periksa` BETWEEN '$tgl_awal' 
              AND '$tgl_akhir' ;



                    ";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

            $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'Sensus Rajal' => [
                        'data' => $data,
                        'titles' => array_keys($data[0])
                    ]
                ]
            ]);
            $file->send("Sensus Rawat Inap $tgl_awal $tgl_akhir.xlsx"); 
        }

        return $this->render('sensus_ranap');
    }

    public function actionSemuaPasien(){
        $sql = "SELECT * FROM pasien";
        $connection = yii::$app->db;
        $command = $connection->createCommand($sql);
        $data = $command->queryAll();

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'Pasien' => [
                    'data' => $data,
                    'titles' => array_keys($data[0])
                ]
            ]
        ]);
        $file->send("Pasien Rumah Sakit.xlsx"); 

    }

    public function actionTopDiagnosis()
    {
        $post_data = Yii::$app->request->post();

        if(!empty($post_data)){
            $connection = yii::$app->db;
            $tgl_awal = $post_data['tgl_awal'];
            $tgl_akhir = $post_data['tgl_akhir'];
        
            $sql = "SELECT 
                        CONCAT(kode, ' - ', `nama_diagnosis`) AS diagnosis,
                        COUNT(1) AS jml 
                      FROM
                        rm_diagnosis 
                        JOIN rekam_medis USING (rm_id) 
                        JOIN kunjungan USING (kunjungan_id) 
                      WHERE kunjungan.`tanggal_periksa` BETWEEN '$tgl_awal' 
                        AND '$tgl_akhir' 
                      GROUP BY kode 
                      ORDER BY jml DESC";
            //$sql = "CALL getTop10DiagnosisPerTgl('$tgl_start','$tgl_end')";
            $command = $connection->createCommand($sql);
            $t = $command->queryAll();
            $d = [];
            foreach ($t as $key => $value) {
                $d[$key]['name'] = strlen($value['diagnosis'])>50 ? substr($value['diagnosis'],0,50).'....' : $value['diagnosis'] ;
                $d[$key]['full_name'] = $value['diagnosis'];
                $d[$key]['y'] = intval($value['jml']);
            }

            return $this->render('top_diagnosis',compact('d'));

        }

        return $this->render('top_diagnosis');
    }

    public function shapeRL34($data){
        
        $shaped_data = [
            0 => [
                'no' => '1',
                'nama' => 'Persalinan Normal',
            ],
            1 => [
                'no' => '2.1',
                'nama' => 'Perd sbl Persalinan',
            ],
            2 => [
                'no' => '2.2',
                'nama' => 'Perd sdh Persalinan',
            ],
            3 => [
                'no' => '2.3',
                'nama' => 'Pre Eclampsi',
            ],
            4 => [
                'no' => '2.4',
                'nama' => 'Eclampsi',
            ],
            5 => [
                'no' => '2.5',
                'nama' => 'Infeksi',
            ],
            6 => [
                'no' => '2.6',
                'nama' => 'Lain - Lain',
            ],
            7 => [
                'no' => '3',
                'nama' => 'Sectio caesaria',
            ],
            8 => [
                'no' => '4',
                'nama' => 'Abortus',
            ],
            9 => [
                'no' => '5.1',
                'nama' => 'Imunisasi - TT1',
            ],
            10 => [
                'no' => '5.2',
                'nama' => 'Imunisasi - TT2',
            ],
        ];

        foreach($shaped_data as $key=>$val)
            for($i=3;$i<=16;$i++)
                $shaped_data[$key]['K_'.$i] = 0;

        $icd34 = [
            0 => ['O80'],
            1 => ['O46'],
            2 => ['O72'],
            3 => ['O14'],
            4 => ['O15'],
            5 => ['O85','O86','O87','O88','O89','O90'],
            6 => ['O80','O46','O72','O14','O15','O85','O86','O87','O88','O89','O90','O82','O03','O04','O05','O06'],
            7 => ['O82'],
            8 => ['O03','O04','O05','O06']
        ];

        /*
            1.  O80
            2.1 O46
            2.2 O72
            2.3 O14
            2.4 O15
            2.5 O85 - O90
            2.6 SELAIN INI
            3   O82
            4   O03 - O06
            5.1 Tidak Ada
            5.2 Tidak Ada

            3 = REFF_TP_07
            4 = REFF_TP_03
            5 = REFF_TP_05
            6 = REFF_TP_01 OR REFF_TP_02 OR REFF_TP_06
            7 = reff NOT NULL (OUT_TP_01 OR OUT_TP_03)
            8 = reff NOT NULL (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07) 
            9 = reff NOT NULL
            10 = (REFF_TP_00 OR REFF_TP_04) AND (OUT_TP_01 OR OUT_TP_03)
            11 = (REFF_TP_00 OR REFF_TP_04) AND (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07) 
            12 = (REFF_TP_00 OR REFF_TP_04)
            13 = reff NULL AND (OUT_TP_01 OR OUT_TP_03)
            14 = reff NULL AND (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07)
            15 = reff NULL AND
            16 = OUT_TP_02

        */

        foreach ($data as $key => $value) {
            for($i=0;$i<=8;$i++){
                if($i==6){
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_07') 
                    $shaped_data[$i]['K_3'] += $value['TOT'];
                
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_03') 
                        $shaped_data[$i]['K_4'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_05') 
                        $shaped_data[$i]['K_5'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_01' || $value['reff_tp'] == 'REFF_TP_02' || $value['reff_tp'] == 'REFF_TP_06')) 
                        $shaped_data[$i]['K_6'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_7'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_8'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_9'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03'))
                        $shaped_data[$i]['K_10'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07'))
                        $shaped_data[$i]['K_11'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04'))
                        $shaped_data[$i]['K_12'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_13'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_14'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_15'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['out_tp']='OUT_TP_02')
                        $shaped_data[$i]['K_16'] += $value['TOT'];
                } else {
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_07') 
                        $shaped_data[$i]['K_3'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_03') 
                        $shaped_data[$i]['K_4'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_05') 
                        $shaped_data[$i]['K_5'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_01' || $value['reff_tp'] == 'REFF_TP_02' || $value['reff_tp'] == 'REFF_TP_06')) 
                        $shaped_data[$i]['K_6'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_7'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_8'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_9'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03'))
                        $shaped_data[$i]['K_10'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07'))
                        $shaped_data[$i]['K_11'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04'))
                        $shaped_data[$i]['K_12'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_13'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_14'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_15'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && $value['out_tp']='OUT_TP_02')
                        $shaped_data[$i]['K_16'] += $value['TOT'];
                }
                
            }

            
        }
        return $shaped_data;
    }

    public function shapeRL35($data){
        
        $shaped_data = [
            0 => [
                'no' => '1',
                'nama' => 'BAYI LAHIR HIDUP',
            ],
            1 => [
                'no' => '1.1',
                'nama' => '≥ 2500 gram',
            ],
            2 => [
                'no' => '1.2',
                'nama' => '< 2500 gram',
            ],
            3 => [
                'no' => '2',
                'nama' => 'KEMATIAN PERINATAL',
            ],
            4 => [
                'no' => '2.1',
                'nama' => 'Kelahiran Mati',
            ],
            5 => [
                'no' => '2.2',
                'nama' => 'Mati Neonatal < 7 Hari',
            ],
            6 => [
                'no' => '3',
                'nama' => 'SEBAB KEMATIAN',
            ],
            7 => [
                'no' => '3.1',
                'nama' => 'Asphyxia',
            ],
            8 => [
                'no' => '3.2',
                'nama' => 'Trauma Kelahiran',
            ],
            9 => [
                'no' => '3.3',
                'nama' => 'BBLR',
            ],
            10 => [
                'no' => '3.4',
                'nama' => 'Tetanus Neonatorum',
            ],
            11 => [
                'no' => '3.5',
                'nama' => 'Kelainan Congenital',
            ],
            12 => [
                'no' => '3.6',
                'nama' => 'ISPA',
            ],
            13 => [
                'no' => '3.7',
                'nama' => 'Diare',
            ],
            14 => [
                'no' => '3.8',
                'nama' => 'Lain - Lain',
            ],
        ];

        foreach($shaped_data as $key=>$val)
            for($i=3;$i<=16;$i++){
                if($key==0 || $key==3 || $key==6)
                    $shaped_data[$key]['K_'.$i] = '';
                else
                    $shaped_data[$key]['K_'.$i] = 0;
            }
        

        $icd35 = [
            0 => ['O80'],
            1 => ['O46'],
            2 => ['O72'],
            3 => ['O14'],
            4 => ['O15'],
            5 => ['O85','O86','O87','O88','O89','O90'],
            6 => ['O80','O46','O72','O14','O15','O85','O86','O87','O88','O89','O90','O82','O03','O04','O05','O06'],
            7 => ['O82'],
            8 => ['O03','O04','O05','O06']
        ];

        /*
            1.  O80
            2.1 O46
            2.2 O72
            2.3 O14
            2.4 O15
            2.5 O85 - O90
            2.6 SELAIN INI
            3   O82
            4   O03 - O06
            5.1 Tidak Ada
            5.2 Tidak Ada

            3 = REFF_TP_07
            4 = REFF_TP_03
            5 = REFF_TP_05
            6 = REFF_TP_01 OR REFF_TP_02 OR REFF_TP_06
            7 = reff NOT NULL (OUT_TP_01 OR OUT_TP_03)
            8 = reff NOT NULL (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07) 
            9 = reff NOT NULL
            10 = (REFF_TP_00 OR REFF_TP_04) AND (OUT_TP_01 OR OUT_TP_03)
            11 = (REFF_TP_00 OR REFF_TP_04) AND (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07) 
            12 = (REFF_TP_00 OR REFF_TP_04)
            13 = reff NULL AND (OUT_TP_01 OR OUT_TP_03)
            14 = reff NULL AND (OUT_TP_04 OR OUT_TP_06 OR OUT_TP_07)
            15 = reff NULL AND
            16 = OUT_TP_02

        */

        foreach ($data as $key => $value) {
            for($i=0;$i<=8;$i++){
                if($i==6){
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_07') 
                    $shaped_data[$i]['K_3'] += $value['TOT'];
                
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_03') 
                        $shaped_data[$i]['K_4'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_05') 
                        $shaped_data[$i]['K_5'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_01' || $value['reff_tp'] == 'REFF_TP_02' || $value['reff_tp'] == 'REFF_TP_06')) 
                        $shaped_data[$i]['K_6'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_7'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_8'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_9'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03'))
                        $shaped_data[$i]['K_10'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07'))
                        $shaped_data[$i]['K_11'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04'))
                        $shaped_data[$i]['K_12'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_13'] += $value['TOT'];
                    
                    if(!in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_14'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_15'] += $value['TOT'];

                    if(!in_array($value['icd_root'], $icd34[$i]) && $value['out_tp']='OUT_TP_02')
                        $shaped_data[$i]['K_16'] += $value['TOT'];
                } else {
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_07') 
                        $shaped_data[$i]['K_3'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_03') 
                        $shaped_data[$i]['K_4'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && $value['reff_tp'] == 'REFF_TP_05') 
                        $shaped_data[$i]['K_5'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_01' || $value['reff_tp'] == 'REFF_TP_02' || $value['reff_tp'] == 'REFF_TP_06')) 
                        $shaped_data[$i]['K_6'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_7'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_8'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_9'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03'))
                        $shaped_data[$i]['K_10'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04')  && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07'))
                        $shaped_data[$i]['K_11'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && ($value['reff_tp'] == 'REFF_TP_00' || $value['reff_tp'] == 'REFF_TP_04'))
                        $shaped_data[$i]['K_12'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_01' || $value['out_tp'] == 'OUT_TP_03')) 
                        $shaped_data[$i]['K_13'] += $value['TOT'];
                    
                    if(in_array($value['icd_root'], $icd34[$i]) && empty($value['reff_tp']) && ($value['out_tp'] == 'OUT_TP_04' || $value['out_tp'] == 'OUT_TP_06' || $value['out_tp'] == 'OUT_TP_07')) 
                        $shaped_data[$i]['K_14'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && !empty($value['reff_tp'])) 
                        $shaped_data[$i]['K_15'] += $value['TOT'];

                    if(in_array($value['icd_root'], $icd34[$i]) && $value['out_tp']='OUT_TP_02')
                        $shaped_data[$i]['K_16'] += $value['TOT'];
                }
                
            }

            
        }
        return $shaped_data;
    }

    public function getCaraBayar($tahun)
    {
        $connection = Yii::$app->db; 
        $sql ="SELECT a.cara_nama as nama,COUNT(b.kunjungan_id) as jumlah FROM cara_bayar a LEFT JOIN (SELECT * FROM kunjungan WHERE 
            year(tanggal_periksa)=$tahun) as b ON a.cara_id=b.cara_id GROUP BY a.cara_nama ORDER BY a.cara_nama ";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

            $d[$key]['name'] = strlen($value['nama'])>50 ? substr($value['nama'],0,50).'....' : $value['nama'] ;
            $d[$key]['y'] = intval($value['jumlah']);
        }
        return $d;
    }

    public function getRuangRanap()
    {
        $connection = Yii::$app->db; 
        $sql ="SELECT bangsal_nm as bangsal,count(ruang_cd) 'jumlah' FROM ruang a LEFT JOIN bangsal b ON a.bangsal_cd=b.bangsal_cd GROUP BY 
            bangsal_nm ORDER BY bangsal_nm";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

            $d[$key]['name'] = strlen($value['bangsal'])>50 ? substr($value['bangsal'],0,50).'....' : $value['bangsal'] ;
            $d[$key]['y'] = intval($value['jumlah']);
        }
        return $d;
    }

    public function getAlasanKunjungan($tgl_start,$tgl_end)
    {
        $connection = Yii::$app->db; 
        $sql ="SELECT a.jns_kunjungan_nama as nama,COUNT(b.kunjungan_id) as jumlah FROM jenis_kunjungan a LEFT JOIN (SELECT * FROM kunjungan WHERE tanggal_periksa BETWEEN '$tgl_start' AND '$tgl_end') as b ON a.jns_kunjungan_id=b.jns_kunjungan_id GROUP BY a.jns_kunjungan_id ";
        //$sql = "CALL getTop10DiagnosisPerTgl('$tgl_start','$tgl_end')";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

            $d[$key]['name'] = strlen($value['nama'])>50 ? substr($value['nama'],0,50).'....' : $value['nama'] ;
            $d[$key]['y'] = intval($value['jumlah']);
        }
        return $d;
    }

    public function getTipeKunjungan($tgl_start,$tgl_end)
    {
        $connection = Yii::$app->db; 
        $sql ="SELECT tipe_kunjungan as tipe, COUNT(kunjungan_id) as jumlah FROM kunjungan WHERE tanggal_periksa BETWEEN '$tgl_start' AND '$tgl_end' 
            GROUP BY tipe_kunjungan ";
        //$sql = "CALL getTop10DiagnosisPerTgl('$tgl_start','$tgl_end')";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

            $d[$key]['name'] = strlen($value['tipe'])>50 ? substr($value['tipe'],0,50).'....' : $value['tipe'] ;
            $d[$key]['y'] = intval($value['jumlah']);
        }
        return $d;
    }



    public function getUnitMedis()
    {
        $connection = Yii::$app->db; 
        $sql ="SELECT medunit_tp as tipe,COUNT(medunit_cd) as jumlah FROM `unit_medis` WHERE medunit_tp='Poliklinik' GROUP BY medunit_tp UNION SELECT b.medunit_nm,COUNT(a.medunit_cd) FROM `unit_medis_item` a, unit_medis b WHERE a.medunit_cd=b.medunit_cd GROUP BY medunit_nm";
        $command = $connection->createCommand($sql);
        $t = $command->queryAll();
        $d = [];
        foreach ($t as $key => $value) {

            $d[$key]['name'] = strlen($value['tipe'])>50 ? substr($value['tipe'],0,50).'....' : $value['tipe'] ;
            $d[$key]['y'] = intval($value['jumlah']);
        }
        return $d;
    }



    public function actionRekapPengunjung($thn)
    {
        $laporan = new Laporan();
        if(null !== Yii::$app->request->post()){
            $p = Yii::$app->request->post();
            $thn = isset($p['cari-pengunjung']) ? $p['cari-pengunjung']:$thn;
        }
        $laporan->setTahun($thn);

        $SQL = "SELECT x.pria,y.perempuan,x.pria+y.perempuan as jumlah  from
        (SELECT COUNT(b.jk) as 'pria' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Laki-Laki') AND (a.tipe_kunjungan='Rawat Jalan')) as x,
        (SELECT COUNT(b.jk) as 'perempuan' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Perempuan') AND (a.tipe_kunjungan='Rawat Jalan')) as y ";
        $rajal = Kunjungan::findBySql($SQL)->asArray()->all();

        $SQL2 = "SELECT x.pria,y.perempuan,x.pria+y.perempuan as jumlah  from
        (SELECT COUNT(b.jk) as 'pria' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Laki-Laki') AND (a.tipe_kunjungan='Rawat Inap')) as x,
        (SELECT COUNT(b.jk) as 'perempuan' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Perempuan') AND (a.tipe_kunjungan='Rawat Inap')) as y ";
        $ranap = Kunjungan::findBySql($SQL2)->asArray()->all();

        //*********************************

        $SQLpenjamin = "SELECT x.penjamin,y.nonpenjamin,x.penjamin+y.nonpenjamin as jumlah  from
        (SELECT COUNT(b.mr) as 'penjamin' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id>2) AND (a.tipe_kunjungan='Rawat Jalan')) as x,
        (SELECT COUNT(b.jk) as 'nonpenjamin' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id<=2) AND (a.tipe_kunjungan='Rawat Jalan')) as y ";
        $jalan_penjamin = Kunjungan::findBySql($SQLpenjamin)->asArray()->all();

        $SQLpenjamin2 = "SELECT x.penjamin,y.nonpenjamin,x.penjamin+y.nonpenjamin as jumlah  from
        (SELECT COUNT(b.mr) as 'penjamin' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id>2) AND (a.tipe_kunjungan='Rawat Inap')) as x,
        (SELECT COUNT(b.jk) as 'nonpenjamin' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id<=2) AND (a.tipe_kunjungan='Rawat Inap')) as y ";
        $inap_penjamin = Kunjungan::findBySql($SQLpenjamin2)->asArray()->all();

        //*********************************
        $SQLrajalpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $rajalpenjaminfrek1 = Kunjungan::findBySql($SQLrajalpenjaminfrek1)->asArray()->all();   

        $SQLrajalpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $rajalpenjaminfrek2 = Kunjungan::findBySql($SQLrajalpenjaminfrek2)->asArray()->all();  

        $SQLrajalpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $rajalpenjaminfrek3 = Kunjungan::findBySql($SQLrajalpenjaminfrek3)->asArray()->all(); 

        $SQLrajalpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $rajalpenjaminfrek4 = Kunjungan::findBySql($SQLrajalpenjaminfrek4)->asArray()->all(); 

        $SQLrajalpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $rajalpenjaminfrek5 = Kunjungan::findBySql($SQLrajalpenjaminfrek5)->asArray()->all(); 

        $SQLrajalpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $rajalpenjaminfrek6 = Kunjungan::findBySql($SQLrajalpenjaminfrek6)->asArray()->all(); 

        $SQLrajalpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $rajalpenjaminfrek10 = Kunjungan::findBySql($SQLrajalpenjaminfrek10)->asArray()->all(); 

        //*********************************
        $SQLinappenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $inappenjaminfrek1 = Kunjungan::findBySql($SQLinappenjaminfrek1)->asArray()->all();   

        $SQLinappenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $inappenjaminfrek2 = Kunjungan::findBySql($SQLinappenjaminfrek2)->asArray()->all();  

        $SQLinappenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $inappenjaminfrek3 = Kunjungan::findBySql($SQLinappenjaminfrek3)->asArray()->all(); 

        $SQLinappenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $inappenjaminfrek4 = Kunjungan::findBySql($SQLinappenjaminfrek4)->asArray()->all(); 

        $SQLinapenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $inappenjaminfrek5 = Kunjungan::findBySql($SQLinapenjaminfrek5)->asArray()->all(); 

        $SQLinappenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $inappenjaminfrek6 = Kunjungan::findBySql($SQLinappenjaminfrek6)->asArray()->all(); 

        $SQLinappenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $inappenjaminfrek10 = Kunjungan::findBySql($SQLinappenjaminfrek10)->asArray()->all();        

        //*********************************
        $SQLrajalnonpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $rajalnonpenjaminfrek1 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek1)->asArray()->all();   

        $SQLrajalnonpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $rajalnonpenjaminfrek2 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek2)->asArray()->all();  

        $SQLrajalnonpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $rajalnonpenjaminfrek3 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek3)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $rajalnonpenjaminfrek4 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek4)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $rajalnonpenjaminfrek5 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek5)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $rajalnonpenjaminfrek6 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek6)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $rajalnonpenjaminfrek10 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek10)->asArray()->all();  

        //*********************************
        $SQLinapnonpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $inapnonpenjaminfrek1 = Kunjungan::findBySql($SQLinapnonpenjaminfrek1)->asArray()->all();   

        $SQLinapnonpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $inapnonpenjaminfrek2 = Kunjungan::findBySql($SQLinapnonpenjaminfrek2)->asArray()->all();  

        $SQLinapnonpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $inapnonpenjaminfrek3 = Kunjungan::findBySql($SQLinapnonpenjaminfrek3)->asArray()->all(); 

        $SQLinapnonpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $inapnonpenjaminfrek4 = Kunjungan::findBySql($SQLinapnonpenjaminfrek4)->asArray()->all(); 

        $SQLinapnonpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $inapnonpenjaminfrek5 = Kunjungan::findBySql($SQLinapnonpenjaminfrek5)->asArray()->all(); 

        $SQLinapnonpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $inapnonpenjaminfrek6 = Kunjungan::findBySql($SQLinapnonpenjaminfrek6)->asArray()->all(); 

        $SQLinapnonpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $inapnonpenjaminfrek10 = Kunjungan::findBySql($SQLinapnonpenjaminfrek10)->asArray()->all();
        
        $laporan = new Laporan();
        $laporan->setTahun($thn);


        return $this->render('rekap-pengunjung',compact('rajal','ranap','jalan_penjamin','inap_penjamin','inappenjaminfrek1','inappenjaminfrek2','inappenjaminfrek3','inappenjaminfrek4','inappenjaminfrek5','inappenjaminfrek6','inappenjaminfrek10','rajalpenjaminfrek1','rajalpenjaminfrek2','rajalpenjaminfrek3','rajalpenjaminfrek4','rajalpenjaminfrek5','rajalpenjaminfrek6','rajalpenjaminfrek10','rajalnonpenjaminfrek1','rajalnonpenjaminfrek2','rajalnonpenjaminfrek3','rajalnonpenjaminfrek4','rajalnonpenjaminfrek5','rajalnonpenjaminfrek6','rajalnonpenjaminfrek10','inapnonpenjaminfrek1','inapnonpenjaminfrek2','inapnonpenjaminfrek3','inapnonpenjaminfrek4','inapnonpenjaminfrek5','inapnonpenjaminfrek6','inapnonpenjaminfrek10','laporan'));
    }

    public function actionUnduhRekapPengunjung($thn)
    {

        $SQL = "SELECT COUNT(b.jk) as 'jumlahPria' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Laki-Laki') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $lakijalan = Kunjungan::findBySql($SQL)->asArray()->all();

        $SQL2 = "SELECT COUNT(b.jk) as 'jumlahPria' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Laki-Laki') AND (a.tipe_kunjungan='Rawat Inap') ";
        $lakiinap = Kunjungan::findBySql($SQL2)->asArray()->all();

        $SQL3 = "SELECT COUNT(b.jk) as 'jumlahWanita' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Perempuan') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $wanitajalan = Kunjungan::findBySql($SQL3)->asArray()->all();

        $SQL4 = "SELECT COUNT(b.jk) as 'jumlahWanita' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (b.jk='Perempuan') AND (a.tipe_kunjungan='Rawat Inap') ";
        $wanitainap = Kunjungan::findBySql($SQL4)->asArray()->all();  

        //*********************************

        $SQLpenjamin = "SELECT COUNT(b.mr) as 'jumlah' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id>2) AND (a.tipe_kunjungan='Rawat Jalan') ";
        $jalan_penjamin = Kunjungan::findBySql($SQLpenjamin)->asArray()->all();

        $SQLpenjamin2 = "SELECT COUNT(b.mr) as 'jumlah' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id>2) AND (a.tipe_kunjungan='Rawat Inap') ";
        $inap_penjamin = Kunjungan::findBySql($SQLpenjamin2)->asArray()->all();

        $SQLpenjamin3 = "SELECT COUNT(b.mr) as 'jumlah' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id<=2) AND (a.tipe_kunjungan='Rawat Jalan') ";
        $jalan_non_penjamin = Kunjungan::findBySql($SQLpenjamin3)->asArray()->all();

        $SQLpenjamin4 = "SELECT COUNT(b.mr) as 'jumlah' FROM kunjungan a, pasien b WHERE (a.mr = b.mr) AND (year(a.tanggal_periksa)=$thn) AND (a.cara_id<=2) AND (a.tipe_kunjungan='Rawat Inap') ";
        $inap_non_penjamin = Kunjungan::findBySql($SQLpenjamin4)->asArray()->all();     

        //*********************************
        $SQLrajalpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $rajalpenjaminfrek1 = Kunjungan::findBySql($SQLrajalpenjaminfrek1)->asArray()->all();   

        $SQLrajalpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $rajalpenjaminfrek2 = Kunjungan::findBySql($SQLrajalpenjaminfrek2)->asArray()->all();  

        $SQLrajalpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $rajalpenjaminfrek3 = Kunjungan::findBySql($SQLrajalpenjaminfrek3)->asArray()->all(); 

        $SQLrajalpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $rajalpenjaminfrek4 = Kunjungan::findBySql($SQLrajalpenjaminfrek4)->asArray()->all(); 

        $SQLrajalpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $rajalpenjaminfrek5 = Kunjungan::findBySql($SQLrajalpenjaminfrek5)->asArray()->all(); 

        $SQLrajalpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $rajalpenjaminfrek6 = Kunjungan::findBySql($SQLrajalpenjaminfrek6)->asArray()->all(); 

        $SQLrajalpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $rajalpenjaminfrek10 = Kunjungan::findBySql($SQLrajalpenjaminfrek10)->asArray()->all(); 

        //*********************************
        $SQLinappenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $inappenjaminfrek1 = Kunjungan::findBySql($SQLinappenjaminfrek1)->asArray()->all();   

        $SQLinappenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $inappenjaminfrek2 = Kunjungan::findBySql($SQLinappenjaminfrek2)->asArray()->all();  

        $SQLinappenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $inappenjaminfrek3 = Kunjungan::findBySql($SQLinappenjaminfrek3)->asArray()->all(); 

        $SQLinappenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $inappenjaminfrek4 = Kunjungan::findBySql($SQLinappenjaminfrek4)->asArray()->all(); 

        $SQLinapenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $inappenjaminfrek5 = Kunjungan::findBySql($SQLinapenjaminfrek5)->asArray()->all(); 

        $SQLinappenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $inappenjaminfrek6 = Kunjungan::findBySql($SQLinappenjaminfrek6)->asArray()->all(); 

        $SQLinappenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id > 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $inappenjaminfrek10 = Kunjungan::findBySql($SQLinappenjaminfrek10)->asArray()->all();        

        //*********************************
        $SQLrajalnonpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $rajalnonpenjaminfrek1 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek1)->asArray()->all();   

        $SQLrajalnonpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $rajalnonpenjaminfrek2 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek2)->asArray()->all();  

        $SQLrajalnonpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $rajalnonpenjaminfrek3 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek3)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $rajalnonpenjaminfrek4 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek4)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $rajalnonpenjaminfrek5 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek5)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $rajalnonpenjaminfrek6 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek6)->asArray()->all(); 

        $SQLrajalnonpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Jalan') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $rajalnonpenjaminfrek10 = Kunjungan::findBySql($SQLrajalnonpenjaminfrek10)->asArray()->all();  

        //*********************************
        $SQLinapnonpenjaminfrek1 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=1)";
        $inapnonpenjaminfrek1 = Kunjungan::findBySql($SQLinapnonpenjaminfrek1)->asArray()->all();   

        $SQLinapnonpenjaminfrek2 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=2)";
        $inapnonpenjaminfrek2 = Kunjungan::findBySql($SQLinapnonpenjaminfrek2)->asArray()->all();  

        $SQLinapnonpenjaminfrek3 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=3)";
        $inapnonpenjaminfrek3 = Kunjungan::findBySql($SQLinapnonpenjaminfrek3)->asArray()->all(); 

        $SQLinapnonpenjaminfrek4 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=4)";
        $inapnonpenjaminfrek4 = Kunjungan::findBySql($SQLinapnonpenjaminfrek4)->asArray()->all(); 

        $SQLinapnonpenjaminfrek5 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan=5)";
        $inapnonpenjaminfrek5 = Kunjungan::findBySql($SQLinapnonpenjaminfrek5)->asArray()->all(); 

        $SQLinapnonpenjaminfrek6 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>=6 AND x.jml_kunjungan<=10)";
        $inapnonpenjaminfrek6 = Kunjungan::findBySql($SQLinapnonpenjaminfrek6)->asArray()->all(); 

        $SQLinapnonpenjaminfrek10 = "SELECT COUNT(x.mr) AS jumlah FROM ( SELECT a.mr, COUNT(a.kunjungan_id) as jml_kunjungan FROM kunjungan a, pasien b WHERE (year(a.tanggal_periksa)=$thn) AND (a.tipe_kunjungan='Rawat Inap') AND (a.mr=b.mr) AND (a.cara_id <= 2) GROUP BY a.mr ) as x, pasien as y WHERE (x.mr=y.mr) AND (x.jml_kunjungan>10)";
        $inapnonpenjaminfrek10 = Kunjungan::findBySql($SQLinapnonpenjaminfrek10)->asArray()->all();
        
        $laporan = new Laporan();
        $laporan->setTahun($thn);

        $content = $this->render('unduh-rekap-pengunjung',compact('lakijalan','lakiinap','wanitajalan','wanitainap','jalan_penjamin','inap_penjamin','jalan_non_penjamin','inap_non_penjamin','inappenjaminfrek1','inappenjaminfrek2','inappenjaminfrek3','inappenjaminfrek4','inappenjaminfrek5','inappenjaminfrek6','inappenjaminfrek10','rajalpenjaminfrek1','rajalpenjaminfrek2','rajalpenjaminfrek3','rajalpenjaminfrek4','rajalpenjaminfrek5','rajalpenjaminfrek6','rajalpenjaminfrek10','rajalnonpenjaminfrek1','rajalnonpenjaminfrek2','rajalnonpenjaminfrek3','rajalnonpenjaminfrek4','rajalnonpenjaminfrek5','rajalnonpenjaminfrek6','rajalnonpenjaminfrek10','inapnonpenjaminfrek1','inapnonpenjaminfrek2','inapnonpenjaminfrek3','inapnonpenjaminfrek4','inapnonpenjaminfrek5','inapnonpenjaminfrek6','inapnonpenjaminfrek10','laporan'));

        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            //'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'filename' => "Rekap_Pengunjung".$thn.".pdf",
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:0px}', 
            //'cssInline' => '.modal-footer{text-align:left;}div.modal-body{padding:0px;}',
             // set mPDF properties on the fly
            'options' => ['title' => "Rekap Pengunjung RSUD Berau ".$thn],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>["Rekap Pengunjung RSUD Berau: ".$thn], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionRekapKunjunganRajal($thn)
    {
        $laporan = new Laporan();
        $laporan->setTahun($thn);

        $SQL = "SELECT COUNT(a.baru_lama) as 'jumlahBaru' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Baru') AND (a.tipe_kunjungan='Rawat Inap') ";
        $ranapbaru = Kunjungan::findBySql($SQL)->asArray()->all();
        $SQL2 = "SELECT COUNT(a.baru_lama) as 'jumlahLama' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Lama') AND (a.tipe_kunjungan='Rawat Inap') ";
        $ranaplama = Kunjungan::findBySql($SQL2)->asArray()->all();
        $SQL3 = "SELECT COUNT(a.baru_lama) as 'jumlahBaru' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Baru') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $rajalbaru = Kunjungan::findBySql($SQL3)->asArray()->all();
        $SQL4 = "SELECT COUNT(a.baru_lama) as 'jumlahLama' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Lama') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $rajallama = Kunjungan::findBySql($SQL4)->asArray()->all();
        //********************************************************************************************************************************
        $SQL5=  "SELECT x.medunit_cd as medunit_cd,x.medunit_nm as Nama_Unit,
                    IF(y.jumlah IS NULL,0,y.jumlah) as Baru,
                    IF(z.jumlah IS NULL,0,z.jumlah) as Lama, 
                    CASE 
                        WHEN y.jumlah IS NOT NULL AND z.jumlah IS NOT NULL THEN y.jumlah+z.jumlah
                        WHEN y.jumlah IS NULL AND z.jumlah IS NULL THEN 0
                        WHEN y.jumlah IS NULL AND z.jumlah IS NOT NULL THEN z.jumlah
                        WHEN y.jumlah IS NOT NULL AND z.jumlah IS NULL THEN y.jumlah
                    END as Total 
                from unit_medis x 
                    LEFT JOIN 
                        (SELECT a.medunit_cd as medunit_cd,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND
                        (a.baru_lama='Baru') GROUP BY a.medunit_cd) as y 
                    ON (x.medunit_cd=y.medunit_cd) 
                    LEFT JOIN 
                        (SELECT b.medunit_cd as medunit_cd,COUNT(b.kunjungan_id) as jumlah FROM kunjungan b WHERE (year(b.tanggal_periksa)=$thn) AND 
                        (b.baru_lama='Lama') GROUP BY b.medunit_cd) as z 
                    ON (x.medunit_cd=z.medunit_cd) 
                GROUP BY x.medunit_cd";
        $kunjungan_klinik_tujuan = Kunjungan::findBySql($SQL5)->asArray()->all();
        //********************************************************************************************************************************
        $SQL6=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan') 
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<1 GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_0_1 = Kunjungan::findBySql($SQL6)->asArray()->all();
        $SQL7=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan') 
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)>=1
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<=4
                         GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_1_4 = Kunjungan::findBySql($SQL7)->asArray()->all();
        $SQL8=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)>=5
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<=14
                         GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_5_14 = Kunjungan::findBySql($SQL8)->asArray()->all();
        $SQL9=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)>=15
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<=24
                         GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_15_24 = Kunjungan::findBySql($SQL9)->asArray()->all();
        $SQL10=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)>=25
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<=44
                         GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_25_44 = Kunjungan::findBySql($SQL10)->asArray()->all();
        $SQL11=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)>=45
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 365)<=64
                         GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_45_64 = Kunjungan::findBySql($SQL11)->asArray()->all();
        $SQL12=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 360)>=65 
                        GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_65 = Kunjungan::findBySql($SQL12)->asArray()->all();
        $SQL13=  "SELECT x.cara_id as cara_id,x.cara_nama as cara_nama,if(y.jumlah is NULL,0,y.jumlah) as Jumlah 
                from cara_bayar x 
                    LEFT JOIN 
                        (SELECT a.cara_id as cara_id,COUNT(a.kunjungan_id) as jumlah FROM kunjungan a, pasien b WHERE 
                            (year(a.tanggal_periksa)=$thn) AND (a.mr=b.mr) 
                            AND (a.tipe_kunjungan='Rawat Jalan')
                            AND (DATEDIFF(a.tanggal_periksa,b.tanggal_lahir) DIV 360)>=0 
                        GROUP BY a.cara_id) as y 
                    ON (x.cara_id=y.cara_id) 
                WHERE x.cara_id > 2 
                GROUP BY x.cara_id ";
        $kunjungan_umur_penjamin_total = Kunjungan::findBySql($SQL13)->asArray()->all();
        //********************************************************************************************************************************

        return $this->render('rekap-kunjungan-rajal',compact('laporan','ranapbaru','ranaplama','rajalbaru','rajallama','kunjungan_klinik_tujuan','kunjungan_umur_penjamin_0_1','kunjungan_umur_penjamin_1_4','kunjungan_umur_penjamin_5_14','kunjungan_umur_penjamin_15_24','kunjungan_umur_penjamin_25_44','kunjungan_umur_penjamin_45_64','kunjungan_umur_penjamin_65','kunjungan_umur_penjamin_total'));
    }

    public function actionRekapKunjunganRanap($thn)
    {
        $laporan = new Laporan();
        if(null !== Yii::$app->request->post()){
            $p = Yii::$app->request->post();
            $thn = isset($p['cari-kunjungan']) ? $p['cari-kunjungan']:$thn;
        }
        $laporan->setTahun($thn);

        $SQL = "SELECT COUNT(a.baru_lama) as 'jumlahBaru' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Baru') AND (a.tipe_kunjungan='Rawat Inap') ";
        $ranapbaru = Kunjungan::findBySql($SQL)->asArray()->all();
        $SQL2 = "SELECT COUNT(a.baru_lama) as 'jumlahLama' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Lama') AND (a.tipe_kunjungan='Rawat Inap') ";
        $ranaplama = Kunjungan::findBySql($SQL2)->asArray()->all();
        $SQL3 = "SELECT COUNT(a.baru_lama) as 'jumlahBaru' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Baru') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $rajalbaru = Kunjungan::findBySql($SQL3)->asArray()->all();
        $SQL4 = "SELECT COUNT(a.baru_lama) as 'jumlahLama' FROM kunjungan a WHERE (year(a.tanggal_periksa)=$thn) AND (a.baru_lama='Lama') AND (a.tipe_kunjungan='Rawat Jalan') ";
        $rajallama = Kunjungan::findBySql($SQL4)->asArray()->all();
        //********************************************************************************************************************************
        $SQL5 = "SELECT x.bangsal_cd as bangsal_cd, x.bangsal_nm as bangsal_nm,y.kelas_cd as kelas_cd,y.jumlah as jumlah from bangsal x LEFT JOIN (SELECT a.bangsal_cd as bangsal_cd,a.kelas_cd as kelas_cd, COUNT(a.ruang_cd) as jumlah FROM `ruang` a GROUP BY a.bangsal_cd, a.kelas_cd) as y ON (x.bangsal_cd=y.bangsal_cd) GROUP BY x.bangsal_cd ";
        $jumlahTT = Ruang::findBySql($SQL5)->asArray()->all();
        $SQL6 = "SELECT distinct kelas_cd from ruang ";
        $kelas = Ruang::findBySql($SQL6)->asArray()->all();

        return $this->render('rekap-kunjungan-ranap',compact('laporan','ranapbaru','ranaplama','rajalbaru','rajallama','jumlahTT','kelas'));
    }

    public function actionRekapKunjunganShri($thn)
    {
        $laporan = new Laporan();
        if(null !== Yii::$app->request->post()){
            $p = Yii::$app->request->post();
            $thn = isset($p['cari-rekap']) ? $p['cari-rekap']:$thn;
        }
        $laporan->setTahun($thn);

        $SQL = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-01-01') AND 
            (date(jam_selesai)>='$thn-01-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_januari = Kunjungan::findBySql($SQL)->asArray()->all();

        $SQL2 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-02-01') AND 
            (date(jam_selesai)>='$thn-02-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_februari = Kunjungan::findBySql($SQL2)->asArray()->all();

        $SQL3 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-03-01') AND 
            (date(jam_selesai)>='$thn-03-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_maret = Kunjungan::findBySql($SQL3)->asArray()->all();

        $SQL4 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-04-01') AND 
            (date(jam_selesai)>='$thn-04-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_april = Kunjungan::findBySql($SQL4)->asArray()->all();

        $SQL5 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-05-01') AND 
            (date(jam_selesai)>='$thn-05-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_mei = Kunjungan::findBySql($SQL5)->asArray()->all();

        $SQL6 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-06-01') AND 
            (date(jam_selesai)>='$thn-06-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_juni = Kunjungan::findBySql($SQL6)->asArray()->all();

        $SQL7 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-07-01') AND 
            (date(jam_selesai)>='$thn-07-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_juli = Kunjungan::findBySql($SQL7)->asArray()->all();

        $SQL8 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-08-01') AND 
            (date(jam_selesai)>='$thn-08-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_agustus = Kunjungan::findBySql($SQL8)->asArray()->all();

        $SQL9 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-09-01') AND 
            (date(jam_selesai)>='$thn-09-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_september = Kunjungan::findBySql($SQL9)->asArray()->all();

        $SQL10 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-10-01') AND 
            (date(jam_selesai)>='$thn-10-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_oktober = Kunjungan::findBySql($SQL10)->asArray()->all();

        $SQL11 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-11-01') AND 
            (date(jam_selesai)>='$thn-11-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_november = Kunjungan::findBySql($SQL11)->asArray()->all();

        $SQL12 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa)<'$thn-12-01') AND 
            (date(jam_selesai)>='$thn-12-01' OR date(jam_selesai) is NULL)";
        $pasien_awal_desember = Kunjungan::findBySql($SQL12)->asArray()->all();
        //**********************************************************

        $SQL13 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-01-01' AND '$thn-01-31') AND 
            (date(jam_selesai)>='$thn-01-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_januari = Kunjungan::findBySql($SQL13)->asArray()->all();

        $SQL14 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) >= '$thn-02-01' AND date(tanggal_periksa) < '$thn-03-01') AND
            (date(jam_selesai)>='$thn-02-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_februari = Kunjungan::findBySql($SQL14)->asArray()->all();

        $SQL15 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-03-01' AND '$thn-03-31') AND
            (date(jam_selesai)>='$thn-03-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_maret = Kunjungan::findBySql($SQL15)->asArray()->all();

        $SQL16 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-04-01' AND '$thn-04-30') AND
            (date(jam_selesai)>='$thn-04-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_april = Kunjungan::findBySql($SQL16)->asArray()->all();

        $SQL17 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-05-01' AND '$thn-05-31') AND 
            (date(jam_selesai)>='$thn-05-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_mei = Kunjungan::findBySql($SQL17)->asArray()->all();

        $SQL18 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-06-01' AND '$thn-06-30') AND
            (date(jam_selesai)>='$thn-06-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_juni = Kunjungan::findBySql($SQL18)->asArray()->all();

        $SQL19 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-07-01' AND '$thn-07-31') AND
            (date(jam_selesai)>='$thn-07-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_juli = Kunjungan::findBySql($SQL19)->asArray()->all();

        $SQL20 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-08-01' AND '$thn-08-31') AND
            (date(jam_selesai)>='$thn-08-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_agustus = Kunjungan::findBySql($SQL20)->asArray()->all();

        $SQL21 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-09-01' AND '$thn-09-30') AND
            (date(jam_selesai)>='$thn-09-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_september = Kunjungan::findBySql($SQL21)->asArray()->all();

        $SQL22 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-10-01' AND '$thn-10-31') AND
            (date(jam_selesai)>='$thn-10-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_oktober = Kunjungan::findBySql($SQL22)->asArray()->all();

        $SQL23 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-11-01' AND '$thn-11-30') AND
            (date(jam_selesai)>='$thn-11-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_november = Kunjungan::findBySql($SQL23)->asArray()->all();

        $SQL24 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-12-01' AND '$thn-12-31') AND 
            (date(jam_selesai)>='$thn-12-01' OR date(jam_selesai) is NULL)";
        $pasien_masuk_desember = Kunjungan::findBySql($SQL24)->asArray()->all();
        //**********************************************************

        $SQL25 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-01-01' AND '$thn-01-31') AND 
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_januari = Kunjungan::findBySql($SQL25)->asArray()->all();

        $SQL26 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) >= '$thn-02-01' AND date(tanggal_periksa) < '$thn-03-01') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_februari = Kunjungan::findBySql($SQL26)->asArray()->all();

        $SQL27 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-03-01' AND '$thn-03-31') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_maret = Kunjungan::findBySql($SQL27)->asArray()->all();

        $SQL28 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-04-01' AND '$thn-04-30') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_april = Kunjungan::findBySql($SQL28)->asArray()->all();

        $SQL29 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-05-01' AND '$thn-05-31') AND 
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_mei = Kunjungan::findBySql($SQL29)->asArray()->all();

        $SQL30 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-06-01' AND '$thn-06-30') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_juni = Kunjungan::findBySql($SQL30)->asArray()->all();

        $SQL31 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-07-01' AND '$thn-07-31') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_juli = Kunjungan::findBySql($SQL31)->asArray()->all();

        $SQL32 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-08-01' AND '$thn-08-31') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_agustus = Kunjungan::findBySql($SQL32)->asArray()->all();

        $SQL33 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-09-01' AND '$thn-09-30') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_september = Kunjungan::findBySql($SQL33)->asArray()->all();

        $SQL34 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-10-01' AND '$thn-10-31') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_oktober = Kunjungan::findBySql($SQL34)->asArray()->all();

        $SQL35 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-11-01' AND '$thn-11-30') AND
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_november = Kunjungan::findBySql($SQL35)->asArray()->all();

        $SQL36 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-12-01' AND '$thn-12-31') AND 
            (jenis_keluar='Hidup')";
        $pasien_keluarhidup_desember = Kunjungan::findBySql($SQL36)->asArray()->all();
        //**********************************************************

        $SQL37 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-01-01' AND '$thn-01-31') AND 
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_januari = Kunjungan::findBySql($SQL37)->asArray()->all();

        $SQL38 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) >= '$thn-02-01' AND date(tanggal_periksa) < '$thn-03-01') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_februari = Kunjungan::findBySql($SQL38)->asArray()->all();

        $SQL39 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-03-01' AND '$thn-03-31') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_maret = Kunjungan::findBySql($SQL39)->asArray()->all();

        $SQL40 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-04-01' AND '$thn-04-30') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_april = Kunjungan::findBySql($SQL40)->asArray()->all();

        $SQL41 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-05-01' AND '$thn-05-31') AND 
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_mei = Kunjungan::findBySql($SQL41)->asArray()->all();

        $SQL42 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-06-01' AND '$thn-06-30') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_juni = Kunjungan::findBySql($SQL42)->asArray()->all();

        $SQL43 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-07-01' AND '$thn-07-31') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_juli = Kunjungan::findBySql($SQL43)->asArray()->all();

        $SQL44 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-08-01' AND '$thn-08-31') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_agustus = Kunjungan::findBySql($SQL44)->asArray()->all();

        $SQL45 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-09-01' AND '$thn-09-30') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_september = Kunjungan::findBySql($SQL45)->asArray()->all();

        $SQL46 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-10-01' AND '$thn-10-31') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_oktober = Kunjungan::findBySql($SQL46)->asArray()->all();

        $SQL47 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-11-01' AND '$thn-11-30') AND
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_november = Kunjungan::findBySql($SQL47)->asArray()->all();

        $SQL48 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-12-01' AND '$thn-12-31') AND 
            (jenis_keluar='Mati < 48 Jam')";
        $pasien_mati_desember = Kunjungan::findBySql($SQL48)->asArray()->all();
        //**********************************************************

        $SQL49 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-01-01' AND '$thn-01-31') AND 
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_januari = Kunjungan::findBySql($SQL49)->asArray()->all();

        $SQL50 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) >= '$thn-02-01' AND date(tanggal_periksa) < '$thn-03-01') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_februari = Kunjungan::findBySql($SQL50)->asArray()->all();

        $SQL51 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-03-01' AND '$thn-03-31') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_maret = Kunjungan::findBySql($SQL51)->asArray()->all();

        $SQL52 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-04-01' AND '$thn-04-30') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_april = Kunjungan::findBySql($SQL52)->asArray()->all();

        $SQL53 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-05-01' AND '$thn-05-31') AND 
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_mei = Kunjungan::findBySql($SQL53)->asArray()->all();

        $SQL54 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-06-01' AND '$thn-06-30') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_juni = Kunjungan::findBySql($SQL54)->asArray()->all();

        $SQL55 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-07-01' AND '$thn-07-31') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_juli = Kunjungan::findBySql($SQL55)->asArray()->all();

        $SQL56 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-08-01' AND '$thn-08-31') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_agustus = Kunjungan::findBySql($SQL56)->asArray()->all();

        $SQL57 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-09-01' AND '$thn-09-30') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_september = Kunjungan::findBySql($SQL57)->asArray()->all();

        $SQL58 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-10-01' AND '$thn-10-31') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_oktober = Kunjungan::findBySql($SQL58)->asArray()->all();

        $SQL59 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-11-01' AND '$thn-11-30') AND
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_november = Kunjungan::findBySql($SQL59)->asArray()->all();

        $SQL60 = "SELECT count(*) as jumlah FROM kunjungan WHERE (tipe_kunjungan = 'Rawat Inap') AND 
            (date(tanggal_periksa) BETWEEN '$thn-12-01' AND '$thn-12-31') AND 
            (jenis_keluar='Mati > 48 Jam')";
        $pasien_mati2_desember = Kunjungan::findBySql($SQL60)->asArray()->all();
        //**********************************************************

        $SQL61 = "SELECT month(jam_selesai) as bulan,SUM(datediff(date(jam_selesai),date(jam_masuk))+1) as jumlah
            FROM kunjungan
            WHERE tipe_kunjungan='Rawat Inap' and year(jam_selesai)=$thn
            GROUP BY month(jam_selesai)";
        $lama_dirawat = Kunjungan::findBySql($SQL61)->asArray()->all();

        return $this->render('rekap-kunjungan-shri',compact('laporan','pasien_awal_januari','pasien_awal_februari','pasien_awal_maret','pasien_awal_april','pasien_awal_mei','pasien_awal_juni','pasien_awal_juli','pasien_awal_agustus','pasien_awal_september','pasien_awal_oktober','pasien_awal_november','pasien_awal_desember','pasien_masuk_januari','pasien_masuk_februari','pasien_masuk_maret','pasien_masuk_april','pasien_masuk_mei','pasien_masuk_juni','pasien_masuk_juli','pasien_masuk_agustus','pasien_masuk_september','pasien_masuk_oktober','pasien_masuk_november','pasien_masuk_desember','pasien_keluarhidup_januari','pasien_keluarhidup_februari','pasien_keluarhidup_maret','pasien_keluarhidup_april','pasien_keluarhidup_mei','pasien_keluarhidup_juni','pasien_keluarhidup_juli','pasien_keluarhidup_agustus','pasien_keluarhidup_september','pasien_keluarhidup_oktober','pasien_keluarhidup_november','pasien_keluarhidup_desember','pasien_mati_januari','pasien_mati_februari','pasien_mati_maret','pasien_mati_april','pasien_mati_mei','pasien_mati_juni','pasien_mati_juli','pasien_mati_agustus','pasien_mati_september','pasien_mati_oktober','pasien_mati_november','pasien_mati_desember','pasien_mati2_januari','pasien_mati2_februari','pasien_mati2_maret','pasien_mati2_april','pasien_mati2_mei','pasien_mati2_juni','pasien_mati2_juli','pasien_mati2_agustus','pasien_mati2_september','pasien_mati2_oktober','pasien_mati2_november','pasien_mati2_desember','lama_dirawat'));
    }

}
