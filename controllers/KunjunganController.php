<?php

namespace app\controllers;

use Yii;
use app\models\Kunjungan;
use app\models\Ruang;
use app\models\Sensus;
use app\models\CaraBayar;
use app\models\KunjunganSearch;
use app\models\KunjunganNapzaSearch;
use app\models\CreateRanapSearch;
use app\models\Konfigurasi;
use app\models\RmDiagnosis;
use app\models\RmDiagnosisBanding;
use app\models\RmObat;
use app\models\RmObatRacik;
use app\models\RmObatRacikKomponen;
use app\models\RmTindakan;
use app\models\RekamMedis;
use app\models\Pasien;
use app\models\Dokter;
use app\models\TarifTindakanSearch;
use app\models\TarifTindakan;



use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * KunjunganController implements the CRUD actions for Kunjungan model.
 */
class KunjunganController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                   'class' => AccessControl::className(),
                   'ruleConfig' => [
                       'class' => AccessRule::className(),
                   ],
                   'rules' => [
                       [
                           'actions' => ['mutasi', 'index','create', 'create2', 'update', 'delete','view','pemeriksaan','bayar','process','cari-pasien','sensus','statistik-pasien','tarif-tindakan'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_ADMIN,User::ROLE_DOKTER_ADMIN,User::ROLE_PETUGAS_RUANG
                           ],
                       ],
                       [
                           'actions' => ['create-rawat-inap','update-rawat-inap','mutasi','create-ranap','index','create', 'update', 'delete','view','cari-pasien','sensus'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_PENDAFTARAN,
                           ],
                       ],
                       [
                           'actions' => ['pemeriksaan','view','mutasi','pemeriksaan-rajal','pemeriksaan-ranap'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_DOKTER
                           ],
                       ],
                       [
                           'actions' => ['list-bebas-napza'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_LAB
                           ],
                       ],
                       [
                           'actions' => ['bayar','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_KASIR
                           ],
                       ],
                       [
                           'actions' => ['farmasi','view','farmasi-rajal','farmasi-ranap'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_FARMASI
                           ],
                       ],
                       [
                           'actions' => ['tracking','view','ketemu','batal-ketemu','kirim','batal-kirim','kembali','batal-kembali','rekap-kunjungan'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_RM
                           ],
                       ],
                       [
                           'actions' => ['rekap-kunjungan','surveilans','sensus'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_CODING
                           ],
                       ]
                   ],
            ],
        ];
    }

    public function actionListBebasNapza(){
        $jenis = 'rj';
        $searchModel = new KunjunganNapzaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list-bebas-napza', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis' => $jenis
        ]);
    }

    public function actionRekapKunjungan(){
        $post_data = Yii::$app->request->post();
        if(!empty($post_data)){
          $connection = Yii::$app->db;

          $tgl_awal = $post_data['tgl_awal'];
          $tgl_akhir = $post_data['tgl_akhir'];
          $sql = "
                  SELECT 
                    `kunjungan_id`,
                    `parent_id`,
                    `tipe_kunjungan`,
                    `baru_lama`,
                    `jns_kunjungan_nama`,
                    pasien.`mr` AS no_rm,
                    pasien.`nama` AS nama_pasien,
                    TIMESTAMPDIFF(YEAR,pasien.tanggal_lahir,tanggal_periksa) AS umur,
                    jk,
                    `medunit_nm` AS poli,
                    `tanggal_periksa`,
                    dokter.`nama` AS nama_dokter,
                    `cara_nama`,
                    `asal_nama`,
                     pulang_nama,
                    `insurance_nm` AS asuransi,
                    `rm_ketemu`,
                    `rm_dikirim`,
                    `rm_kembali`,
                    GROUP_CONCAT(
                      CONCAT(
                        rm_diagnosis.`kode`,
                        ' - ',
                        rm_diagnosis.`nama_diagnosis`,
                        '( Kasus ',
                        rm_diagnosis.`kasus`
                      ),
                      ')'
                    ) AS diagnosa,
                    GROUP_CONCAT(
                      CONCAT(
                        rm_diagnosis_banding.`kode`,
                        ' - ',
                        rm_diagnosis_banding.`nama_diagnosis`,
                        '( Kasus ',
                        rm_diagnosis_banding.`kasus`,
                        ')'
                      )
                    ) AS diagnosa_banding,
                    GROUP_CONCAT(
                      CONCAT(
                        rm_tindakan.`tindakan_cd`,
                        ' - ',
                        rm_tindakan.`nama_tindakan`
                      )
                    ) AS tindakan,
                    GROUP_CONCAT(
                      CONCAT(
                        `rm_tindakan_coding`.`kode`,
                        ' - ',
                        rm_tindakan_coding.`short_desc`
                      )
                    ) AS tindakan_coding 
                  FROM
                    `kunjungan` 
                    LEFT JOIN pasien USING (mr) 
                    LEFT JOIN cara_bayar USING (cara_id) 
                    LEFT JOIN jenis_kunjungan USING (jns_kunjungan_id) 
                    LEFT JOIN unit_medis USING (medunit_cd) 
                    LEFT JOIN asal_pasien USING (asal_id) 
                    LEFT JOIN ref_cara_pulang ON pulang_id = jenis_keluar
                    LEFT JOIN asuransi USING (insurance_cd) 
                    LEFT JOIN dokter 
                      ON dokter.`user_id` = kunjungan.`dpjp` 
                    LEFT JOIN rekam_medis USING (kunjungan_id) 
                    LEFT JOIN rm_diagnosis USING (rm_id) 
                    LEFT JOIN `rm_diagnosis_banding` USING (rm_id) 
                    LEFT JOIN `rm_tindakan` USING (rm_id) 
                    LEFT JOIN `rm_tindakan_coding` USING (rm_id) 
                  WHERE tanggal_periksa BETWEEN '$tgl_awal' 
                    AND '$tgl_akhir' 
                  GROUP BY kunjungan.`kunjungan_id` ";
          $command = $connection->createCommand($sql);
          $data = $command->queryAll();

          $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [

                'Order Supplier' => [   // Name of the excel sheet
                    'data' => $data,

                    // Set to `false` to suppress the title row
                    'titles' => [
                        'Id',
                        'Parent Id',  
                        'Tipe Kunjungan', 
                        'Bara Lama' ,
                        'Jenis Kunjungan',
                        'No RM',
                        'Nama Pasien',
                        'Umur',
                        'Jenis Kelamin',
                        'poli',
                        'tanggal_periksa',
                        'Nama Dokter',
                        'Cara Bayar',
                        'Asal Pasien',
                        'Cara Pulang',
                        'Asuransi',
                        'rm_ketemu',  
                        'rm_dikirim', 
                        'rm_kembali',
                        'Diagnosis',
                        'Diagnosis Banding',
                        'Tindakan',
                        'Tindakan Coding'
                    ],

                    'formats' => [
                        // Either column name or 0-based column index can be used
                        //'C' => '#,##0.00',
                        //3 => 'dd/mm/yyyy hh:mm:ss',
                    ],

                    'formatters' => [
                        // Dates and datetimes must be converted to Excel format
                        // 3 => function ($value, $row, $data) {
                        //     return \PHPExcel_Shared_Date::PHPToExcel(strtotime($value));
                        // },
                    ],
                ],

            ]
          ]);
          $file->send("Rekap Kunjungan $tgl_awal s/d $tgl_akhir.xlsx"); 

        } else {
          return $this->render('rekap_kunjungan');
        }
    }

    public function actionSurveilans(){
        $model = new Kunjungan();
        $this->layout = 'report';
        if(!empty(Yii::$app->request->post())){
            $post_data = Yii::$app->request->post();
            $data = $model->getSurveilans($post_data['start_date'],$post_data['end_date'],$post_data['tipe_kunjungan'],$post_data['jenis_diagnosa']);
            return $this->render('surveilans_result',['data'=>$data]);
        }
        return $this->render('surveilans');
    }

    /**
     * Lists all Kunjungan models.
     * @return mixed
     */
    public function actionIndex($jenis=null)
    {
        if($jenis=='null') $jenis = 'rj';
        $cara_bayar = ArrayHelper::map(CaraBayar::find()->asArray()->all(), 'cara_id', 'cara_nama');
        $searchModel = new KunjunganSearch();



        if($jenis=='rj')
          $dataProvider = $searchModel->searchRjPendaftaran(Yii::$app->request->queryParams,false,false,'antri',$jenis);
        else
          $dataProvider = $searchModel->searchRiPendaftaran(Yii::$app->request->queryParams,false,false,'antri',$jenis);


        if(Yii::$app->request->post('hasEditable')){
            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);
            $kunjungan = ['Kunjungan'=>current(Yii::$app->request->post('Kunjungan'))];

            $model->load($kunjungan);
            $model->save();
            return Json::encode(['output'=>$cara_bayar[$model->cara_id],'message'=>'']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis' => $jenis
        ]);
    }

    public function actionSensus(){
        $post_data = Yii::$app->request->post();

        if(!empty($post_data)){
            $connection = yii::$app->db;
            $tgl_awal = $post_data['tgl_awal'];
            $tgl_akhir = $post_data['tgl_akhir'];
            $sql = "SELECT 
                      kunjungan.`tanggal_periksa`,
                      pasien.`mr`,
                      pasien.`nama`,
                      pasien.`alamat`,
                      pasien.`jk`,
                      pasien.`tanggal_lahir`,
                      kasus,
                      cara_nama AS cara_bayar,
                      GROUP_CONCAT(rm_diagnosis.`kode`) AS icd10,
                      GROUP_CONCAT(rm_diagnosis.`nama_diagnosis`) AS diagnosa,
                      asal_nama AS asal_pasien
                    FROM
                      kunjungan 
                      JOIN rekam_medis USING (kunjungan_id) 
                      JOIN pasien ON rekam_medis.`mr` = pasien.`mr`
                      JOIN rm_diagnosis USING (rm_id) 
                      LEFT JOIN cara_bayar USING (cara_id) 
                      LEFT JOIN asal_pasien USING (asal_id) 
                    WHERE tanggal_periksa BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    GROUP BY kunjungan_id ";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();

            $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'Sensus Diagnosa' => [
                        'data' => $data,
                        'titles' => array_keys($data[0])
                    ]
                ]
            ]);
            $file->send("Sensus Diagnosa $tgl_awal $tgl_akhir.xlsx"); 
        }

        return $this->render('sensus');

    }

    public function actionCariPasien()
    {
        $post_data = Yii::$app->request->post();
        $klinik_id = Yii::$app->user->identity->klinik_id;

        $byTgl = '';
        if ($post_data['keyword_3']!="" && strpos($post_data['keyword_3'], '_') === false){
            $k3 = @date('Y-m-d', strtotime($post_data['keyword_3']));
            $byTgl = " tanggal_lahir LIKE '%".$k3."%' AND ";
        }
        $query = Pasien::findBySql("
            SELECT * FROM pasien
            WHERE (
                mr LIKE '%".$post_data['keyword']."%' OR
                nama LIKE '%".$post_data['keyword']."%') AND ".$byTgl."
                alamat LIKE '%".$post_data['keyword_2']."%'
            LIMIT 50
            
        ");
        return json_encode($query->asArray()->all());
    }

    public function actionCreateRanap()
    {
        $searchModel = new CreateRanapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true,false,'');

        return $this->renderAjax('createRanap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPemeriksaan()
    {
        $searchModel = new KunjunganSearch();

        $the_view = 'pemeriksaan';
        if(!empty(Yii::$app->user->identity->bangsal_cd)){
          $the_view = 'pemeriksaan_ranap';
          $dataProvider = $searchModel->searchBangsalPemeriksaan(Yii::$app->request->queryParams,true,false,'');
        }else{
          $dataProvider = $searchModel->searchDrPemeriksaan(Yii::$app->request->queryParams,true,false,'');
        }

        $dokter = ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama');

        if(Yii::$app->request->post('hasEditable')){
            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);
            $kunjungan = ['Kunjungan'=>current(Yii::$app->request->post('Kunjungan'))];

            $model->load($kunjungan);
            $model->save();
            return Json::encode(['output'=>$dokter[$model->dpjp],'message'=>'']);
        }
        
        return $this->render($the_view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPemeriksaanRajal()
    {
        $searchModel = new KunjunganSearch();
        $the_view = 'pemeriksaan';
        $dataProvider = $searchModel->searchDrPemeriksaan(Yii::$app->request->queryParams,true,false,'');
        $dokter = ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama');

        if(Yii::$app->request->post('hasEditable')){
            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);
            $kunjungan = ['Kunjungan'=>current(Yii::$app->request->post('Kunjungan'))];

            $model->load($kunjungan);
            $model->save();
            return Json::encode(['output'=>$dokter[$model->dpjp],'message'=>'']);
        }
        
        return $this->render($the_view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPemeriksaanRanap()
    {
        $searchModel = new KunjunganSearch();
        $the_view = 'pemeriksaan_ranap';
        $dataProvider = $searchModel->searchBangsalPemeriksaan(Yii::$app->request->queryParams,true,false,'');
        
        
        $dokter = ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama');

        if(Yii::$app->request->post('hasEditable')){
            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);
            $kunjungan = ['Kunjungan'=>current(Yii::$app->request->post('Kunjungan'))];

            $model->load($kunjungan);
            $model->save();
            return Json::encode(['output'=>$dokter[$model->dpjp],'message'=>'']);
        }
        
        return $this->render($the_view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFarmasi()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->searchFarmasi(Yii::$app->request->queryParams,true,true,'');
        
        return $this->render('farmasi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionStatistikPasien()
    {

       
        $bangsal=Yii::$app->user->identity->bangsal_cd;
        $connection = Yii::$app->db;
        $kunjunganTahun="select pasien.jk as nama, count(pasien.mr) as data from kunjungan join pasien on kunjungan.mr=pasien.mr
        where kunjungan.tipe_kunjungan='Rawat Inap' and ruang_cd like '$bangsal%' and year (kunjungan.created)=year(current_date())
        group by pasien.jk ;";
        $command = $connection->createCommand($kunjunganTahun);
        $data = $command->queryAll();
        $isi =[];
        foreach ($data as $index => $row){
            $isi[$index]['name']=$row['nama'];
            $isi[$index]['data']=[(int)$row['data']];
        }
        $kunjunganBulan="select pasien.jk as nama, count(pasien.mr) as data from kunjungan join pasien on kunjungan.mr=pasien.mr
        where kunjungan.tipe_kunjungan='Rawat Inap' and ruang_cd like '$bangsal%' and month (kunjungan.created)=month(current_date())
        group by pasien.jk ;";
        $command = $connection->createCommand($kunjunganBulan);
        $data1 = $command->queryAll();
        $isi1 =[];
        foreach ($data1 as $index => $row){
            $isi1[$index]['name']=$row['nama'];
            $isi1[$index]['data']=[(int)$row['data']];
        }
        
        return   
            $this->render('statistik_pasien',compact('hari_ini','bulan_ini','isi','isi1','isi2','bangsal'));
    }
    
    public function actionTarifTindakan()
    {
         $searchModel = new TarifTindakanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('daftar_tindakan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }

    public function actionFarmasiRajal()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->searchFarmasiRajal(Yii::$app->request->queryParams,true,true,'');
        
        return $this->render('farmasi_ralan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFarmasiRanap()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->searchFarmasiRanap(Yii::$app->request->queryParams,true,true,'');
        return $this->render('farmasi_ranap', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBayar()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true,true,'');

        return $this->render('bayar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTracking()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->searchTrackRm(Yii::$app->request->queryParams,true,true,'');

        return $this->render('tracking', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kunjungan model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionKetemu($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Ketemu';
        $model->rm_ketemu = date('Y-m-d H:i:s');
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    public function actionBatalKetemu($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Datang';
        $model->rm_ketemu = null;
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    public function actionKirim($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Dikirim';
        $model->rm_dikirim = date('Y-m-d H:i:s');
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    public function actionBatalKirim($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Ketemu';
        $model->rm_dikirim = null;
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    public function actionKembali($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Kembali';
        $model->rm_kembali = date('Y-m-d H:i:s');
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    public function actionBatalKembali($id){
        $model = $this->findModel($id);
        $model->status_rm = 'Dikirim';
        $model->rm_kembali = null;
        if($model->save()){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengupdate Status Rekam Medis');
        }
        
        return $this->redirect(['tracking']);
    }

    
    /**
     * Creates a new Kunjungan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($asal = null,$jenis = null)
    {
        $model = new Kunjungan();
        $model->scenario = 'createRawatJalan';
        $post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
            $model->klinik_id = Yii::$app->user->identity->klinik_id;
            $model->tipe_kunjungan = $jenis=='ri'?'Rawat Inap':'Rawat Jalan';
            $model->baru_lama = $model->isBaru($post_data['mr']) ? 'Baru' : 'Lama';
            $model->tanggal_periksa = date('Y-m-d');
            $model->jam_masuk = date('Y-m-d H:i:s');
            $model->created = date('Y-m-d H:i:s');
            $model->status = 'antri';
            $model->user_input = Yii::$app->user->identity->username;
            $model->user_id = Yii::$app->user->identity->id;
            $model->mr = $post_data['mr'];
            $model->medunit_cd = $post_data['Kunjungan']['medunit_cd'];
            $model->jns_kunjungan_id = $post_data['Kunjungan']['jns_kunjungan_id'];
            $model->asal_id = $post_data['Kunjungan']['asal_id'];
            $model->cara_id = $post_data['Kunjungan']['cara_id'];
            $model->dpjp = $post_data['Kunjungan']['dpjp'];
            $model->status_rm = 'Datang';
            $model->save();

            $model_rm = new RekamMedis();
            $model_rm->user_id = $post_data['Kunjungan']['dpjp'];
            $model_rm->kunjungan_id = $model->kunjungan_id;
            $model_rm->mr = $post_data['mr'];
            $model_rm->created = date('Y-m-d H:i:s');
            $model_rm->locked = 0;
            $model_rm->save();

            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menambahkan ke Antrian');

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $model->cara_id = Konfigurasi::getValue('CARA_DEFAULT');
            $model->jns_kunjungan_id = Konfigurasi::getValue('JENIS_KUNJUNGAN');
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateRawatInap($asal = null,$jenis = null)
    {
        $model = new Kunjungan();
        $model->scenario = 'createRawatInap';
        $post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
            $model->klinik_id = Yii::$app->user->identity->klinik_id;
            $model->tipe_kunjungan = 'Rawat Inap';
            $model->baru_lama = $model->isBaru($post_data['mr']) ? 'Baru' : 'Lama';
            $model->tanggal_periksa = date('Y-m-d');
            $model->jam_masuk = date('Y-m-d H:i:s');
            $model->created = date('Y-m-d H:i:s');
            $model->status = 'antri';
            $model->user_input = Yii::$app->user->identity->username;
            $model->user_id = Yii::$app->user->identity->id;
            $model->mr = $post_data['mr'];
            $model->jns_kunjungan_id = $post_data['Kunjungan']['jns_kunjungan_id'];
            $model->asal_id = $post_data['Kunjungan']['asal_id'];
            $model->cara_id = $post_data['Kunjungan']['cara_id'];
            $model->status_rm = 'Datang';

            $model->medunit_cd = null;
            $model->rl_31 = $post_data['Kunjungan']['rl_31'];
            $model->ruang_cd = $post_data['Kunjungan']['ruang_cd'];
            $model->dpjp = $post_data['Kunjungan']['dpjp'];

            if ($model->kelas_cd=='')
                $model->kelas_cd = $model->ruang0->kelas_cd;
            else
                $model->kelas_cd = $model->kelas_cd;

            $model->save();

            $model_rm = new RekamMedis();
            $model_rm->user_id = $post_data['Kunjungan']['dpjp'];
            $model_rm->kunjungan_id = $model->kunjungan_id;
            $model_rm->mr = $post_data['mr'];
            $model_rm->created = date('Y-m-d H:i:s');
            $model_rm->locked = 0;
            $model_rm->save();

            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menambahkan ke Antrian');

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('createRawatInap', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateRawatInap($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'createRawatInap';
        $post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
            $model->user_input = Yii::$app->user->identity->username;
            $model->user_id = Yii::$app->user->identity->id;
            $model->jns_kunjungan_id = $post_data['Kunjungan']['jns_kunjungan_id'];
            $model->asal_id = $post_data['Kunjungan']['asal_id'];
            $model->cara_id = $post_data['Kunjungan']['cara_id'];
            $model->medunit_cd = null;
            $model->rl_31 = $post_data['Kunjungan']['rl_31'];
            $model->ruang_cd = $post_data['Kunjungan']['ruang_cd'];
            $model->dpjp = $post_data['Kunjungan']['dpjp'];

            if ($model->kelas_cd=='')
                $model->kelas_cd = $model->ruang0->kelas_cd;
            else
                $model->kelas_cd = $model->kelas_cd;

            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menambahkan ke Antrian');

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('updateRawatInap', [
                'model' => $model,
            ]);
        }
    }

//tambahan dari umar taufiq
    public function actionCreate2($asal = null,$jenis = null)
    {
        $model = new Kunjungan();
       
        $post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
            $model->klinik_id = Yii::$app->user->identity->klinik_id;
            $model->tipe_kunjungan = $jenis=='ri'?'Rawat Inap':'Rawat Jalan';
            $model->baru_lama = $model->isBaru($post_data['mr']) ? 'Baru' : 'Lama';
            $model->tanggal_periksa = date('Y-m-d');
            $model->jam_masuk = date('Y-m-d H:i:s');
            $model->created = date('Y-m-d H:i:s');
            $model->status = 'antri';
            $model->user_input = Yii::$app->user->identity->username;
            $model->user_id = Yii::$app->user->identity->id;
            $model->mr = $post_data['mr'];
            $model->medunit_cd = $post_data['Kunjungan']['medunit_cd'];
            $model->jns_kunjungan_id = $post_data['Kunjungan']['jns_kunjungan_id'];
            //$model->asal_id = $post_data['Kunjungan']['asal_id'];
            //$model->cara_id = $post_data['Kunjungan']['cara_id'];
            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menambahkan ke Antrian');

            if(!empty($asal)){
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
//akhir tambahan dari umar taufiq

    /**
     * Updates an existing Kunjungan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Mengubah Data');

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMutasi($id, $to = 'all'){
        $model = new Kunjungan();
        $model->scenario = 'mutasi';
        if ($model->load(Yii::$app->request->post())) {
            $mutasi = $model->mutasiTo($id,$model->dpjp);
            if($mutasi!==false){
                \Yii::$app->getSession()->setFlash('success', 'Mutasi Berhasil.');
                if (Yii::$app->user->identity->role==25)
                    return $this->redirect(['kunjungan/pemeriksaan']);
                else{
                    if ($mutasi==null)
                        $mutasi = 'ri'; else $mutasi='rj';
                    return $this->redirect(['kunjungan/index','jenis'=>$mutasi]);
                }
            }else{

                \Yii::$app->getSession()->setFlash('danger', 'Mutasi Gagal.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->renderAjax('mutasi',['model'=>$model,'to'=>$to]);
    }

    public function actionProcess($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');

            return $this->redirect(['view', 'id' => $model->kunjungan_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Kunjungan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $rm_id = RekamMedis::findAll(['kunjungan_id'=>$id]);
        foreach ($rm_id as $key => $value) {
            RmDiagnosis::deleteAll(['rm_id'=>$value['rm_id']]);
            RmDiagnosisBanding::deleteAll(['rm_id'=>$value['rm_id']]);
            RmObat::deleteAll(['rm_id'=>$value['rm_id']]);
            RmTindakan::deleteAll(['rm_id'=>$value['rm_id']]);
            $rm_obatracik = RmObatRacik::findAll(['rm_id'=>$value['rm_id']]);
            foreach ($rm_obatracik as $val)
                RmObatRacikKomponen::deleteAll(['racik_id'=>$val['racik_id']]);
            RmObatRacik::deleteAll(['rm_id'=>$value['rm_id']]);

            $this->findModelRm($rm_id)->delete();
        }

        $model = $this->findModel($id);
        if ($model->medunit_cd==null){
            $ruang = Ruang::findOne($model->ruang_cd);
            $ruang->status = 1;
            $ruang->save();
        }
        $model->delete();
        \Yii::$app->getSession()->setFlash('success', 'Berhasil Menghapus Data');

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Kunjungan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Kunjungan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kunjungan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelRm($id)
    {
        if (($model = RekamMedis::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
