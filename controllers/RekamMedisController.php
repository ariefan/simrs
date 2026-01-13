<?php

namespace app\controllers;

use Yii;
use app\models\Konfigurasi;
use app\models\Pasien;
use app\models\RmDiagnosis;
use app\models\RmPenunjang;
use app\models\RmDiagnosisBanding;
use app\models\RmObat;
use app\models\InvItemMaster;
use app\models\InvItemMove;
use app\models\RmObatRacik;
use app\models\RmObatRacikKomponen;
use app\models\RmTindakan;
use app\models\RmTindakanCoding;
use app\models\EklaimIcd9cm;
use app\models\RmLab;
use app\models\RmRad;
use app\models\RekamMedis;
use app\models\RmInap;
use app\models\RmInapGizi;
use app\models\Kunjungan;
use app\models\Diagnosis;
use app\models\Obat;
use app\models\Tindakan;
use app\models\TarifTindakan;
use app\models\TarifUnitmedis;
use app\models\Dokter;
use app\models\Klinik;
use app\models\RekamMedisSearch;
use app\models\UnitMedisItem;
use app\models\KunjunganEklaim;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;
use kartik\mpdf\Pdf;

/**
 * RekamMedisController implements the CRUD actions for RekamMedis model.
 */
class RekamMedisController extends Controller
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
                   'only' => ['index','create', 'update', 'delete','view'],
                   'rules' => [
                       [
                           'actions' => ['index','create', 'update', 'delete','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_ADMIN,User::ROLE_DOKTER_ADMIN,User::ROLE_DOKTER,User::ROLE_PETUGAS_RUANG
                           ],
                       ],
                       [
                           'actions' => ['index','coding','view','update','create'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_CODING
                           ],
                       ]
                   ],
            ],
        ];
    }

    /**
     * Lists all RekamMedis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RekamMedisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->role=='18')
            return $this->render('index_coding', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionErrorMaxRm()
    {
        return $this->render('error_max_rm');
    }

    /**
     * Displays a single RekamMedis model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );

        //if(!$this->isUserAuthor()) 
        //    throw new NotFoundHttpException('The requested page does not exist.');

        $this->layout = 'main_no_portlet';
        $model = $this->findModel($id);
        $histori_rm = RekamMedis::findAll(['mr'=>$model->mr]);
        $user_m = Dokter::findOne($model->user_id);
        $rm_diagnosis = RmDiagnosis::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_diagnosis_banding = RmDiagnosisBanding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obat = RmObat::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_lab = RmLab::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_rad = RmRad::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$id])/*->asArray()*/->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $pasien = Pasien::findOne(['mr'=>$model->mr]);
        
        $data_penunjang = RmPenunjang::findAll(['rm_id'=>$id]);
        return $this->render('view', compact('model','rm_diagnosis','rm_diagnosis_banding','rm_obat','rm_obatracik','rm_obatracik_komponen','rm_tindakan','pasien','data_penunjang','histori_rm','rm_lab','rm_rad','user_m'));
    }

    public function actionViewAjax($id)
    {
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );

        //if(!$this->isUserAuthor()) 
        //    throw new NotFoundHttpException('The requested page does not exist.');

        $this->layout = 'main_no_portlet';
        $model = $this->findModel($id);
        $histori_rm = RekamMedis::findAll(['mr'=>$model->mr]);
        $user_m = Dokter::findOne($model->user_id);
        $rm_diagnosis = RmDiagnosis::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_diagnosis_banding = RmDiagnosisBanding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obat = RmObat::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_lab = RmLab::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_rad = RmRad::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$id])/*->asArray()*/->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $pasien = Pasien::findOne(['mr'=>$model->mr]);
        
        $data_penunjang = RmPenunjang::findAll(['rm_id'=>$id]);
        return $this->renderAjax('view', compact('model','rm_diagnosis','rm_diagnosis_banding','rm_obat','rm_obatracik','rm_obatracik_komponen','rm_tindakan','pasien','data_penunjang','histori_rm','rm_lab','rm_rad','user_m'));
    }

    public function actionCancelProsesStok($id){
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        $rm_id = $id;
        $transaction = InvItemMove::getDb()->beginTransaction(); 
        
        $rm_obat = RmObat::find()->where(['rm_id'=>$rm_id,'proses_stok'=>1])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$rm_id])->asArray()->all();
        $model = new InvItemMove();
        $pos_farmasi = Konfigurasi::getValue('POS_PELAYANAN');
        $berhasil = true;

        foreach ($rm_obat as $key => $value) {
            $m = RmObat::findOne($value['id']);

            if($m->proses_stok == 1){
                $m->proses_stok = 0;
                $berhasil = $berhasil && $m->save();
                $berhasil = $berhasil && $model->cancelKurangiStok($pos_farmasi,$value['obat_id'],$value['jumlah'],$value['batch_no'],'Cancel Obat Pasien RM ID #'.$rm_id);
            }
            
        }

        foreach ($rm_obatracik as $key => $value) {
            $d = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id'],'proses_stok'=>0])->asArray()->all();
            foreach ($d as $v) {
                $m = RmObatRacikKomponen::findOne($v['id']);
                if($m->proses_stok == 1){
                    $m->proses_stok = 0;
                    $berhasil = $berhasil && $m->save();
                    $berhasil = $berhasil && $model->cancelKurangiStok($pos_farmasi,$v['obat_id'],$v['jumlah'],$v['batch_no'],'Cancel Obat Racik Pasien RM ID #'.$rm_id);
                }
            }
        }
        if($berhasil){
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
            $transaction->commit();
        } else {
            \Yii::$app->getSession()->setFlash('error', 'Terdapat Error');
            $transaction->rollBack();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function prosesStok($rm_id){
        $rm_obat = RmObat::find()->where(['rm_id'=>$rm_id,'proses_stok'=>0])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$rm_id])->asArray()->all();
        $model = new InvItemMove();
        $pos_farmasi = Konfigurasi::getValue('POS_PELAYANAN');
        $berhasil = true;
        foreach ($rm_obat as $key => $value) {
            $batch_used = $model->kurangiStok($pos_farmasi,$value['obat_id'],$value['jumlah'],'Obat Pasien RM ID #'.$rm_id);
            $m = RmObat::findOne($value['id']);
            $m->proses_stok = 1;
            $m->batch_no = intval($batch_used);
            $m->save();
        }

        foreach ($rm_obatracik as $key => $value) {
            $d = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id'],'proses_stok'=>0])->asArray()->all();
            foreach ($d as $v) {
                $batch_used = $model->kurangiStok($pos_farmasi,$v['obat_id'],$v['jumlah'],'Obat Racik Pasien RM ID #'.$rm_id);
                $m = RmObatRacikKomponen::findOne($v['id']);
                $m->proses_stok = 1;
                $m->batch_no = intval($batch_used);
                $berhasil = $berhasil && $m->save();
            }
        }

        return $berhasil;
    }

    public function actionCetakResep($id,$cetak=false)
    {
        $this->layout = 'main_no_portlet';
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        //if(!$this->isUserAuthor()) 
        //    throw new NotFoundHttpException('The requested page does not exist.');
        $model = $this->findModel($id);

        if(!empty(Yii::$app->request->post())){
            $transaction = InvItemMove::getDb()->beginTransaction(); 
            $berhasil = $this->prosesStok($id);
            if($berhasil){
                \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
                $transaction->commit();
            } else {
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error');
                $transaction->rollBack();
            }
            return $this->redirect(['kunjungan/farmasi']);
        }
        $pasien = Pasien::findOne($model->mr);
        $rm_obat = RmObat::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $klinik = Klinik::findOne(Yii::$app->user->identity->klinik_id);
        $dokter = Dokter::findOne($model->user_id);
        return $this->renderAjax('resep',compact('dokter','rm_obat','rm_obatracik','rm_obatracik_komponen','pasien','model','klinik','cetak'));
    }

    public function actionPrintResep($id)
    {
        $this->layout = 'report';
       $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        //if(!$this->isUserAuthor()) 
        //    throw new NotFoundHttpException('The requested page does not exist.');
        $rm = RekamMedis::findOne(['kunjungan_id'=>$id]);

        $model = $this->findModel($rm['rm_id']);

        $pasien = Pasien::findOne($model->mr);
        $rm_obat = RmObat::find()->where(['rm_id'=>$model->rm_id])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$model->rm_id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $klinik = Klinik::findOne(Yii::$app->user->identity->klinik_id);
        $dokter = Dokter::findOne($model->user_id);
        return $this->render('printResep',compact('dokter','rm_obat','rm_obatracik','rm_obatracik_komponen','pasien','model','klinik'));
    }

    public function actionCheckObat($id,$asal)
    {
        $this->layout = 'main_no_portlet';
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        //if(!$this->isUserAuthorCreate()) 
        //    throw new NotFoundHttpException('The requested page does not exist.');

        $model = $this->findModel($id);
        $rm = $model;
        $histori_rm = RekamMedis::findAll(['mr'=>$model->mr]);
        $model_kunjungan = Kunjungan::find()->joinWith('mr0')->where(['kunjungan_id'=>$model->kunjungan_id])->one();

        if($model->load(Yii::$app->request->post())){
            $transaction = RekamMedis::getDb()->beginTransaction(); 

            $post_data = Yii::$app->request->post();

            try{
                $berhasil = true;
                $this->resetRmKomponen($rm['rm_id'],true);

                $berhasil = $berhasil && $model->save();
                $berhasil = $berhasil && $this->saveDataRm($post_data,$berhasil,$model);
                if(isset($post_data['Selesai']))
                    $berhasil = $berhasil && $this->prosesStok($rm['rm_id']);

                if($berhasil){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
                    $id = utf8_encode(Yii::$app->security->encryptByKey( $id, Yii::$app->params['kunciInggris'] ));
                    if(isset($post_data['Simpan'])){
                        return $this->redirect(['check-obat', 'id' => $id,'asal'=>$asal]);
                    } elseif(isset($post_data['Cetak'])){
                        return $this->redirect(['cetak-resep', 'id' => $id,'cetak'=>true]);
                    } elseif(isset($post_data['Selesai'])) {
                        $model_kunjungan = $this->findModelKunjungan($model->kunjungan_id);
                        $model_kunjungan->status = 'selesai';
                        $berhasil = $berhasil && $model_kunjungan->save();

                        return $this->redirect(Yii::$app->request->referrer);
                    }
                } else {
                    throw new \Exception("Terdapat Error");
                }
                
            }  catch(\Exception $e) {
                $kunjungan = Kunjungan::find()->joinWith('mr0')->where(['kunjungan_id'=>$kunjungan_id])->one();

                $transaction->rollBack();
                //throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->render('check_obat', $this->prepareFailData($model,$post_data,$kunjungan,$histori_rm));
            }
            
        }
        
        $rm_obat = RmObat::find()->where(['rm_id'=>$rm['rm_id'],'proses_stok'=>0])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$rm['rm_id']])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id'],'proses_stok'=>0])->asArray()->all();
        }
        $rm_obat_terproses = RmObat::find()->where(['rm_id'=>$rm['rm_id'],'proses_stok'=>1])->asArray()->all();
        $rm_obatracik_komponen_terproses = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen_terproses[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id'],'proses_stok'=>1])->asArray()->all();
        }
        $kunjungan = $model_kunjungan;
        return $this->render('check_obat', compact('model','kunjungan','rm','rm_obat','rm_obatracik','rm_obatracik_komponen','rm_obat_terproses','rm_obatracik_komponen_terproses'));
    }


    public function actionUnduhRm($id)
    {
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');

        $this->layout = 'main_no_portlet';
        $model = $this->findModel($id);

        $rm_diagnosis = RmDiagnosis::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_diagnosis_banding = RmDiagnosisBanding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obat = RmObat::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $pasien = Pasien::findOne(['mr'=>$model->mr]);

        $content = $this->renderAjax('unduh',compact('model','rm_diagnosis','rm_diagnosis_banding','rm_obat','rm_obatracik','rm_obatracik_komponen','rm_tindakan','pasien'));
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
	        'filename' => "RM ".$pasien->mr." ".$model->created.".pdf",
	        // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => "Rekam Medis ".$pasien->mr." ".$model->created],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>["Rekam Medis, No.RM: ".$pasien->mr.", Tanggal Periksa:".$model->created], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    /**
     * Creates a new RekamMedis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($kunjungan_id)
    {
        $modelRmInap = new RmInap;
        $modelRmInapGizi = new RmInapGizi;
        $eklaim = new KunjunganEklaim;
        $kunjungan_id = Yii::$app->security->decryptByKey( utf8_decode($kunjungan_id), Yii::$app->params['kunciInggris'] );
      
        if(!$this->isUserAuthorCreate()) 
            throw new NotFoundHttpException('The requested page does not exist.');
        
        $kunjungan = Kunjungan::find()->joinWith('mr0')->where(['kunjungan_id'=>$kunjungan_id])->one();
        if(RekamMedis::find()->where(['kunjungan_id'=>$kunjungan->kunjungan_id])->count()==0)
            $model = new RekamMedis();
        else{
            if ($kunjungan->tipe_kunjungan=='Rawat Jalan')
                $model = RekamMedis::find()->where(['kunjungan_id'=>$kunjungan->kunjungan_id])->one();
            if ($kunjungan->tipe_kunjungan=='Rawat Inap'){
                if(RekamMedis::find()->where(['kunjungan_id'=>$kunjungan->kunjungan_id,'tgl_periksa'=>date("Y-m-d")])->count()==0)
                    $model = new RekamMedis();
                else
                    $model = RekamMedis::find()->where(['kunjungan_id'=>$kunjungan->kunjungan_id,'tgl_periksa'=>date("Y-m-d")])->one();
            }
        }
        
        $this->layout = 'main_no_portlet';

        // if ($kunjungan->tipe_kunjungan == 'Rawat Inap')
        $model->tgl_periksa = date("Y-m-d");

        if ($kunjungan->eklaim!=false){
            $eklaim = KunjunganEklaim::findOne($kunjungan_id);
            $eklaim->scenario = 'pemeriksaan';
        }

        $model->mr = $kunjungan->mr0->mr;
        $histori_rm = RekamMedis::findAll(['mr'=>$kunjungan->mr0->mr]);
        $model->user_id = Yii::$app->user->identity->id;
        $model->kunjungan_id = $kunjungan_id;
        $model->created = date('Y-m-d H:i:s');
        $transaction = RekamMedis::getDb()->beginTransaction(); 
        $data_lab = UnitMedisItem::getWithSub('LAB00');
        $data_rad = UnitMedisItem::getWithSub('RADIO00');
        $post_data = Yii::$app->request->post();

        if ($model->load($post_data) && $kunjungan->load(Yii::$app->request->post())) {

            if ($kunjungan->eklaim!=false){
                $eklaim->load($post_data);
                $eklaim->save();
            }

            try{
                $berhasil = true;
                $pembagi = (($model->tinggi_badan/100) * ($model->tinggi_badan/100));
                if($pembagi>0)
                    $model->bmi = $model->berat_badan / $pembagi;

                $berhasil = $berhasil && $model->save();
                $berhasil = $berhasil && $kunjungan->save();
                $berhasil = $berhasil && $this->saveDataRm($post_data,$berhasil,$model);

                if($berhasil){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
                    $rm_id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));
                    $model_kunjungan = $this->findModelKunjungan($model->kunjungan_id);
                    if(isset($post_data['Simpan'])){
                        $model_kunjungan->status = 'diperiksa';
                        $berhasil = $berhasil && $model_kunjungan->save();
                        return $this->redirect(['update', 'id' => $rm_id]);
                    } elseif(isset($post_data['Selesai'])) {
                        $model_kunjungan->jam_selesai = date('Y-m-d H:i:s');
                        $model_kunjungan->status = 'antri bayar';
                        $berhasil = $berhasil && $model_kunjungan->save();
                        return $this->redirect(['view', 'id' => $rm_id]);
                    }
                    return $this->redirect(['view', 'id' => $rm_id]);
                } else {                    
                    throw new \Exception("Terdapat Error");
                }
                
            }  catch(\Exception $e) {

                $transaction->rollBack();
                throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');

                return $this->render('create', $this->prepareFailData($model,$post_data,$kunjungan,$histori_rm));
            }
            
        } else {

            return $this->render('create', compact('eklaim','modelRmInap','modelRmInapGizi','model','kunjungan','histori_rm','data_rad','data_lab'));
        }
    }

    /**
     * Updates an existing RekamMedis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $eklaim = new KunjunganEklaim;
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        $model = $this->findModel($id);
//        if(!$this->isUserAuthor() || $model->locked) 
//            throw new NotFoundHttpException('Rekam Medis Sudah Terkunci.');


        $kunjungan = Kunjungan::find()->joinWith('mr0')->where(['kunjungan_id'=>$model->kunjungan_id])->one();

        if ($kunjungan->eklaim!=false){
            $eklaim = KunjunganEklaim::findOne($kunjungan->kunjungan_id);
            $eklaim->scenario = 'pemeriksaan';
        }

        $histori_rm = RekamMedis::findAll(['mr'=>$kunjungan->mr0->mr]);
        $data_lab = UnitMedisItem::getWithSub('LAB00');
        $data_rad = UnitMedisItem::getWithSub('RADIO00');
        $this->layout = 'main_no_portlet';
        $transaction = RekamMedis::getDb()->beginTransaction(); 
        $model->modified = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post()) && $kunjungan->load(Yii::$app->request->post())) {
            $post_data = Yii::$app->request->post();
            // echo '<pre>';
            // print_r($post_data);die;

            if ($kunjungan->eklaim!=false){
                $eklaim->load($post_data);
                $eklaim->save();
            }

            try{
                $berhasil = true;

                $this->resetRmKomponen($id);
                
                $pembagi = (($model->tinggi_badan/100) * ($model->tinggi_badan/100));
                if($pembagi>0)
                    $model->bmi = $model->berat_badan / $pembagi;

                $berhasil = $berhasil && $model->save();
                $berhasil = $berhasil && $kunjungan->save();
                $berhasil = $berhasil && $this->saveDataRm($post_data,$berhasil,$model);

                if($berhasil){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
                    $rm_id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));
                    if(isset($post_data['Simpan'])){
                        return $this->redirect(['update', 'id' => $rm_id]);
                    } elseif(isset($post_data['Selesai'])) {
                        $model_kunjungan = $this->findModelKunjungan($model->kunjungan_id);
                        $model_kunjungan->jam_selesai = date('Y-m-d H:i:s');
                        $model_kunjungan->status = 'antri bayar';
                        $berhasil = $berhasil && $model_kunjungan->save();

                        return $this->redirect(['view', 'id' => $rm_id]);
                    }
                } else {
                    throw new \Exception("Terdapat Error");
                }
                
            }  catch(\Exception $e) {
                $transaction->rollBack();
                //throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->render('update', $this->prepareFailData($model,$post_data,$kunjungan,$histori_rm));
            }
            
        } else {
            $rm_diagnosis_temp = RmDiagnosis::findAll(['rm_id'=>$model->rm_id]);
            $rm_diagnosis_id = [];
            $rm_diagnosis_text = [];
            foreach ($rm_diagnosis_temp as $key => $value) {
                $rm_diagnosis_id[$key] = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'];
                $rm_diagnosis_text[$key]  = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'].' - '.$value['nama_diagnosis'];
            }

            $rm_diagnosis_banding_temp = RmDiagnosisBanding::findAll(['rm_id'=>$model->rm_id]);

            $rm_diagnosis_banding_id = [];
            $rm_diagnosis_banding_text = [];
            foreach ($rm_diagnosis_banding_temp as $key => $value) {
                $rm_diagnosis_banding_id[$key] = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'];
                $rm_diagnosis_banding_text[$key]  = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'].' - '.$value['nama_diagnosis'];
            }

            $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$model->rm_id])->all();
            $modelRmInapGizi = RmInapGizi::find()->where(['rm_id'=>$model->rm_id])->all();

            $rm_lab_temp = RmLab::findAll(['rm_id'=>$model->rm_id]);
            $rm_lab = [];
            foreach ($rm_lab_temp as $key => $value) {
                $rm_lab[$key] = $value['medicalunit_cd'];
            }

            $rm_rad_temp = RmRad::findAll(['rm_id'=>$model->rm_id]);
            $rm_rad = [];
            foreach ($rm_rad_temp as $key => $value) {
                $rm_rad[$key] = $value['medicalunit_cd'];
            }

            $rm_obat = RmObat::findAll(['rm_id'=>$model->rm_id]);
            $rm_obatracik = RmObatRacik::findAll(['rm_id'=>$model->rm_id]);
            $rm_obatracik_komponen = [];
            foreach ($rm_obatracik as $key => $value) $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::findAll(['racik_id'=>$value['racik_id']]);
            $data_exist = [];
            return $this->render('update', compact('modelRmInapGizi','eklaim','model','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','data_exist','histori_rm','data_rad','data_lab','rm_rad','rm_lab'));
        }
    }

    /**
     * Deletes an existing RekamMedis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');
        
        try{
            $id = Yii::$app->security->decryptByKey( utf8_decode(Yii::$app->request->get('id')), Yii::$app->params['kunciInggris'] );
            
            $this->resetRmKomponen($id);
            $this->findModel($id)->delete();
            
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menghapus Rekam Medis');
        } catch(\Exception $e) {
                \Yii::$app->getSession()->setFlash('error', 'Gagal Menghapus Rekam Medis, Karena Sudah Memiliki Data Transaksi');
        }

        

        return $this->redirect(['index']);
    }

    public function actionDeletePenunjang($id)
    {
        $model = $this->findModelPenunjang(Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] ));
        $rm_id = $model->rm_id;
        if(!$this->isUserAuthorPenunjang($rm_id)) 
            throw new NotFoundHttpException('The requested page does not exist.');
        unlink($model->path);
        $model->delete();

        return $this->redirect(['upload','id'=>utf8_encode(Yii::$app->security->encryptByKey( $rm_id, Yii::$app->params['kunciInggris'] ))]);
    }

    public function actionUpload($id)
    {
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');

        $fileName = 'file';
        $uploadPath = 'rm_penunjang';

        if (isset($_FILES[$fileName])) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            $random_str = Yii::$app->security->generateRandomString(5);
            $model = new RmPenunjang();
            $model->rm_id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
            $file->name = str_replace(' ', '_', $file->name);
            $model->path = $uploadPath . '/' . $random_str.$file->name;
            $model->save();
            //Print file data
            //print_r($file);

            if ($file->saveAs($uploadPath . '/' . $random_str.$file->name)) {
                //Now save file data to database

                echo \yii\helpers\Json::encode($file);
            }
        } else {
            $data = RmPenunjang::findAll(['rm_id'=>Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] )]);
            return $this->render('upload',compact('id','data'));
        }

        return false;
    }

    private function prepareFailData($model,$post_data,$kunjungan,$histori_rm)
    {
        $data_lab = UnitMedisItem::getWithSub('LAB00');
        $data_rad = UnitMedisItem::getWithSub('RADIO00');

        $rm_diagnosis_id = [];
        $rm_diagnosis_text = [];
        if(isset($post_data['diagnosis']))
        foreach ($post_data['diagnosis'] as $key => $value) {
            $rm_diagnosis_id[$key] = $value;
            $diagnosis = Diagnosis::findOne(['kode'=>$value]);
            $rm_diagnosis_text[$key]  = empty($diagnosis) ? $value : $value.' - '.$diagnosis['nama'];
        }

        $rm_diagnosis_banding_id = [];
        $rm_diagnosis_banding_text = [];
        if(isset($post_data['diagnosis_banding']))
        foreach ($post_data['diagnosis_banding'] as $key => $value) {
            $rm_diagnosis_banding_id[$key]= $value;
            $diagnosis = Diagnosis::findOne(['kode'=>$value]);
            $rm_diagnosis_banding_text[$key]  = empty($diagnosis) ? $value : $value.' - '.$diagnosis['nama'];
        }

        $rm_tindakan = [];
        if(isset($post_data['tindakan']))
        foreach ($post_data['tindakan'] as $key => $value) {
            $rm_tindakan[$key] = $value;
        }

        $rm_lab = [];
        if(isset($post_data['lab_item']))
        foreach ($post_data['lab_item'] as $key => $value) {
            $rm_lab[$key] = $value;
        }

        $rm_rad = [];
        if(isset($post_data['rad_item']))
        foreach ($post_data['rad_item'] as $key => $value) {
            $rm_rad[$key] = $value;
        }

        $rm_obat = [];
        if(isset($post_data['Obat'])){
            foreach ($post_data['Obat']['jumlah'] as $obat_id => $jumlah) {
                if($obat_id!='resep'){
                    $rm_obat[$obat_id]['obat_id'] = $obat_id;
                    $rm_obat[$obat_id]['jumlah'] = $jumlah;
                    $d = InvItemMaster::findOne($obat_id);
                    $rm_obat[$obat_id]['nama_obat'] = $d['item_nm'];
                    $rm_obat[$obat_id]['satuan'] = $d['unit_cd'];
                    $rm_obat[$obat_id]['signa'] = $post_data['Obat']['signa'][$obat_id];
                }
            }

            if(isset($post_data['Obat']['jumlah']['resep'])){
                foreach($post_data['Obat']['jumlah']['resep'] as $key_resep=>$jumlah){
                    $rm_obat[$key_resep.'a']['obat_id'] = null;
                    $rm_obat[$key_resep.'a']['jumlah'] = $jumlah;
                    $rm_obat[$key_resep.'a']['nama_obat'] = $post_data['Obat']['nama']['resep'][$key_resep];
                    $rm_obat[$key_resep.'a']['satuan'] = $post_data['Obat']['satuan']['resep'][$key_resep];
                    $rm_obat[$key_resep.'a']['signa'] = $post_data['Obat']['signa']['resep'][$key_resep];
                }
            } 
        }

        $rm_obatracik_komponen = [];
        $rm_obatracik = [];
        // echo '<pre>';
        // print_r($post_data['ObatRacik']);exit;
        if(isset($post_data['ObatRacik']))
        foreach ($post_data['ObatRacik'] as $counter => $obatracik) {
            $rm_obatracik[$counter]['jumlah'] = $obatracik['jumlah_pulf'];
            $rm_obatracik[$counter]['signa'] = $obatracik['signa'];
            if(isset($obatracik['jumlah'])){
                if(isset($post_data['Obat']['jumlah']['resep'])){
                    foreach ($obatracik['jumlah'] as $obat_id => $jumlah) {
                        if($obat_id!='resep'){
                            $rm_obatracik_komponen[$counter][$obat_id]['obat_id'] = $obat_id;
                            $d = InvItemMaster::findOne($obat_id);
                            $rm_obatracik_komponen[$counter][$obat_id]['nama_obat'] = $d['item_nm'];
                            $rm_obatracik_komponen[$counter][$obat_id]['satuan'] = $d['unit_cd'];
                            $rm_obatracik_komponen[$counter][$obat_id]['jumlah'] = $jumlah;
                        }
                    }
                    if(isset($obatracik['jumlah']['resep']))
                    foreach($obatracik['jumlah']['resep'] as $key_resep=>$jumlah){
                        $rm_obatracik_komponen[$counter][$key_resep.'a']['obat_id'] = null;
                        $rm_obatracik_komponen[$counter][$key_resep.'a']['jumlah'] = $jumlah;
                        $rm_obatracik_komponen[$counter][$key_resep.'a']['nama_obat'] = $obatracik['nama']['resep'][$key_resep];
                        $rm_obatracik_komponen[$counter][$key_resep.'a']['satuan'] = $obatracik['satuan']['resep'][$key_resep];
                    }
                } 
            }
        } 
        return compact('model','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','histori_rm','data_lab','data_rad');
    }

    // public function actionCb()
    // {
    //     // echo 'asdasd';
    //     $model = new RmDiagnosis();
    //     $model->kode = 'B96.3';
    //     print_r($model->kasusBaruLama(32));
    // }

    private function saveDataRm($post_data,$berhasil,$model)
    {
        if(isset($post_data['diagnosis']))
            foreach ($post_data['diagnosis'] as $key => $value) {
                $RmDiagnosis = new RmDiagnosis();
                $d = Diagnosis::findOne($value);
                $RmDiagnosis->rm_id = $model->rm_id;
                if(empty($d)){
                    $RmDiagnosis->kode = null;
                    $RmDiagnosis->nama_diagnosis = $value;
                } else {
                    $RmDiagnosis->kode = $value;
                    $RmDiagnosis->nama_diagnosis = $d['nama'];
                }

                $RmDiagnosis->kasus = $RmDiagnosis->kasusBaruLama($model->rm_id);
                $berhasil = $berhasil && $RmDiagnosis->save();
            }
        
        if(isset($post_data['diagnosis_banding']))
            foreach ($post_data['diagnosis_banding'] as $key => $value) {
                $RmDiagnosis = new RmDiagnosisBanding();
                $d = Diagnosis::findOne($value);
                $RmDiagnosis->rm_id = $model->rm_id;
                if(empty($d)){
                    $RmDiagnosis->kode = null;
                    $RmDiagnosis->nama_diagnosis = $value;
                } else {
                    $RmDiagnosis->kode = $value;
                    $RmDiagnosis->nama_diagnosis = $d['nama'];
                }
                $RmDiagnosis->kasus = $RmDiagnosis->kasusBaruLama($id);
                $berhasil = $berhasil && $RmDiagnosis->save();
            }

        if(isset($post_data['lab_item']))
            foreach ($post_data['lab_item'] as $key => $value) {
                $RmLab = new RmLab();
                $RmLab->rm_id = $model->rm_id;
                $RmLab->medicalunit_cd = $value;
                $d = UnitMedisItem::findOne($value);
                $RmLab->dokter = Yii::$app->user->identity->id;
                $RmLab->nama = $d['medicalunit_nm'];
                $RmLab->jumlah = 1;

                # SEKARANG TARIF DISIMPAN DARI SINI
                # RJ AMBIL DARI KELAS PALING KECIL
                # RI SESUAI KELASNYA
                if(empty($model->kelas_cd))
                    $tarifs = TarifUnitmedis::find()
                        ->where(['medicalunit_cd'=>$value])
                        ->orderBy('kelas_cd')
                        ->all();
                else
                    $tarifs = TarifUnitmedis::find()
                        ->where(['medicalunit_cd'=>$value,'kelas_cd'=>$model->kelas_cd])
                        ->all();

                foreach ($tarifs as $t_key => $t_value) {
                    $RmLab->tarif_id = $t_value->tarif_unitmedis_id;
                    break;
                }
                
                $RmLab->save();
            }

        if(isset($post_data['rad_item']))
            foreach ($post_data['rad_item'] as $key => $value) {
                $RmRad = new RmRad();
                $RmRad->rm_id = $model->rm_id;
                $RmRad->medicalunit_cd = $value;
                $d = UnitMedisItem::findOne($value);
                $RmRad->dokter = Yii::$app->user->identity->id;
                $RmRad->jumlah = 1;
                $RmRad->nama = $d['medicalunit_nm'];

                # SEKARANG TARIF DISIMPAN DARI SINI
                # RJ AMBIL DARI KELAS PALING KECIL
                # RI SESUAI KELASNYA
                if(empty($model->kelas_cd))
                    $tarifs = TarifUnitmedis::find()
                        ->where(['medicalunit_cd'=>$value])
                        ->orderBy('kelas_cd')
                        ->all();
                else
                    $tarifs = TarifUnitmedis::find()
                        ->where(['medicalunit_cd'=>$value,'kelas_cd'=>$model->kelas_cd])
                        ->all();

                foreach ($tarifs as $t_key => $t_value) {
                    $RmRad->tarif_id = $t_value->tarif_unitmedis_id;
                    break;
                }

                $RmRad->save();
            }

        /*
        $tindakan_wajib = Tindakan::findAll(['klinik_id'=>Yii::$app->user->identity->klinik_id,'biaya_wajib'=>1]);

        foreach ($tindakan_wajib as $value) {
            $RmTindakan = new RmTindakan();
            $RmTindakan->rm_id = $model->rm_id;
            $RmTindakan->tindakan_id = $value['tindakan_id'];
            $d = Tindakan::findOne($value);
            $RmTindakan->nama_tindakan = 'Umum';
            $berhasil = $berhasil && $RmTindakan->save();
        }*/
        
        if(isset($post_data['tindakan']['listTindakan']))
            foreach ($post_data['tindakan']['listTindakan'] as $key => $value) {
                $RmTindakan = new RmTindakan();
                $RmTindakan->rm_id = $model->rm_id;
                $RmTindakan->tindakan_cd = $value;
                $RmTindakan->user_id = $post_data['tindakan']['listDokter'][$key];
                $RmTindakan->jumlah = $post_data['tindakan']['listJumlah'][$key];
                $RmTindakan->created_by = Yii::$app->user->identity->id;

                $d = Tindakan::findOne($value);                
                $RmTindakan->nama_tindakan = $d['nama_tindakan'];

                # SEKARANG TARIF DISIMPAN DARI SINI
                # RJ AMBIL DARI KELAS PALING KECIL
                # RI SESUAI KELASNYA
                if(empty($model->kelas_cd))
                    $tarifs = TarifTindakan::find()
                        ->where(['treatment_cd'=>$value])
                        ->orderBy('kelas_cd')
                        ->all();
                else
                    $tarifs = TarifTindakan::find()
                        ->where(['treatment_cd'=>$value,'kelas_cd'=>$model->kelas_cd])
                        ->all();

                foreach ($tarifs as $t_key => $t_value) {
                    $RmTindakan->tarif_id = $t_value->tarif_tindakan_id;
                    break;
                }
                
                $berhasil = $berhasil && $RmTindakan->save();

            }

        if(isset($post_data['diet']['listDiet']))
            foreach ($post_data['diet']['listDiet'] as $key => $value) {
                $RmGizi = new RmInapGizi();
                $RmGizi->rm_id = $model->rm_id;
                $RmGizi->kode_diet = $value;

                $RmGizi->jam_makan = $post_data['diet']['listJamMakan'][$key];
                $RmGizi->jam_makan_spesifik = $post_data['diet']['listJamMakanSpesifik'][$key];
                $RmGizi->diagnosa = $post_data['diet']['listDiagnosa'][$key];
                
                $berhasil = $berhasil && $RmGizi->save();

            }


        if(isset($post_data['Obat'])){
            foreach ($post_data['Obat']['jumlah'] as $obat_id => $jumlah) {
                if($obat_id!='resep'){
                    $RmObat = new RmObat();
                    $RmObat->rm_id = $model->rm_id;
                    $RmObat->obat_id = $obat_id;
                    $RmObat->jumlah = $jumlah;
                    $RmObat->signa = $post_data['Obat']['signa'][$obat_id];
                    $d = InvItemMaster::findOne($obat_id);
                    $RmObat->nama_obat = $d['item_nm'];
                    $RmObat->satuan = $d['unit_cd'];
                    $berhasil = $berhasil && $RmObat->save();
                }
            }
            if(isset($post_data['Obat']['jumlah']['resep']))
                foreach ($post_data['Obat']['jumlah']['resep'] as $resep_key => $jumlah) {
                    $RmObat = new RmObat();
                    $RmObat->rm_id = $model->rm_id;
                    $RmObat->obat_id = null;
                    $RmObat->jumlah = $jumlah;
                    $RmObat->signa = $post_data['Obat']['signa']['resep'][$resep_key];
                    $RmObat->nama_obat = $post_data['Obat']['nama']['resep'][$resep_key];
                    $RmObat->satuan = $post_data['Obat']['satuan']['resep'][$resep_key];
                    $berhasil = $berhasil && $RmObat->save();
                }
        }
        //echo '<pre>';
        //print_r($post_data['ObatRacik']);exit;
        if(isset($post_data['ObatRacik']))
            foreach ($post_data['ObatRacik'] as $counter => $obatracik) {
                if(isset($obatracik['jumlah'])){
                    $RmObatRacik = new RmObatRacik();
                    $RmObatRacik->rm_id = $model->rm_id;
                    $RmObatRacik->jumlah = $obatracik['jumlah_pulf'];
                    $RmObatRacik->signa = $obatracik['signa'];
                    $berhasil = $berhasil && $RmObatRacik->save();
                    foreach ($obatracik['jumlah'] as $obat_id => $jumlah) {
                        if($obat_id!='resep'){
                            $RmObatRacikKomponen = new RmObatRacikKomponen();
                            $RmObatRacikKomponen->racik_id = $RmObatRacik->racik_id;
                            $RmObatRacikKomponen->obat_id = $obat_id;
                            $RmObatRacikKomponen->jumlah = $jumlah;
                            $d = InvItemMaster::findOne($obat_id);
                            $RmObatRacikKomponen->satuan = $d['unit_cd'];
                            $RmObatRacikKomponen->nama_obat = $d['item_nm'];

                            $berhasil = $berhasil && $RmObatRacikKomponen->save();
                        }
                    }
                    if(isset($obatracik['jumlah']['resep']))
                        foreach ($obatracik['jumlah']['resep'] as $resep_key => $jumlah) {
                            $RmObatRacikKomponen = new RmObatRacikKomponen();
                            $RmObatRacikKomponen->racik_id = $RmObatRacik->racik_id;
                            $RmObatRacikKomponen->obat_id = null;
                            $RmObatRacikKomponen->jumlah = $jumlah;
                            $RmObatRacikKomponen->nama_obat = $obatracik['nama']['resep'][$resep_key];
                            $RmObatRacikKomponen->satuan = $obatracik['satuan']['resep'][$resep_key];
                            
                            $berhasil = $berhasil && $RmObatRacikKomponen->save();
                        }
                }
            }

        return $berhasil;
    }

    private function resetRmKomponen($id,$only_obat = false)
    {
        if(!$only_obat){
            RmDiagnosis::deleteAll(['rm_id'=>$id]);
            RmDiagnosisBanding::deleteAll(['rm_id'=>$id]);
            RmTindakan::deleteAll(['rm_id'=>$id]);
            RmInapGizi::deleteAll(['rm_id'=>$id]);
            RmLab::deleteAll(['rm_id'=>$id]);
            RmRad::deleteAll(['rm_id'=>$id]);
        }
        
        RmObat::deleteAll(['rm_id'=>$id]);
        $rm_obatracik = RmObatRacik::findAll(['rm_id'=>$id]);
        foreach ($rm_obatracik as $key => $value)
            RmObatRacikKomponen::deleteAll(['racik_id'=>$value['racik_id']]);
        RmObatRacik::deleteAll(['rm_id'=>$id]);
    }

    public function actionCasemix($id){
        $this->layout = 'report';
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );
        $model = $this->findModel($id);
        $pasien = Pasien::findOne($model->mr);
        $kunjungan = $this->findModelKunjungan($model->kunjungan_id);
        $rm_diagnosis = RmDiagnosis::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_icd9 = RmTindakanCoding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_diagnosis_banding = RmDiagnosisBanding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$id])->asArray()->all();
        
        return $this->render('casemix',compact('model','rm_diagnosis','rm_icd9','rm_diagnosis_banding','rm_tindakan','pasien','kunjungan'));
    }

    public function actionCoding($id){
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );

        $post_data = Yii::$app->request->post();
        $model = $this->findModel($id);

        $model_kunjungan = $this->findModelKunjungan($model->kunjungan_id);
        if(empty($model_kunjungan->jam_selesai)) $model_kunjungan->jam_selesai = date('Y-m-d H:i:s');
        if ($model_kunjungan->load(Yii::$app->request->post())) {
            $model_kunjungan->save();
        }

        if(!empty($post_data)){
            $berhasil = true;
            if(isset($post_data['diagnosis'])){
                RmDiagnosis::deleteAll(['rm_id'=>$id]);
                foreach ($post_data['diagnosis'] as $key => $value) {
                    $RmDiagnosis = new RmDiagnosis();
                    $d = Diagnosis::findOne($value);
                    $RmDiagnosis->rm_id = $id;
                    if(empty($d)){
                        $RmDiagnosis->kode = null;
                        $RmDiagnosis->nama_diagnosis = $value;
                    } else {
                        $RmDiagnosis->kode = $value;
                        $RmDiagnosis->nama_diagnosis = $d['nama'];
                    }

                    $RmDiagnosis->kasus = $RmDiagnosis->kasusBaruLama($id);

                    $berhasil = $berhasil && $RmDiagnosis->save();
                }
            }


            if(isset($post_data['diagnosis_banding'])){
                RmDiagnosisBanding::deleteAll(['rm_id'=>$id]);
                foreach ($post_data['diagnosis_banding'] as $key => $value) {
                    $RmDiagnosis = new RmDiagnosisBanding();
                    $d = Diagnosis::findOne($value);
                    $RmDiagnosis->rm_id = $id;
                    if(empty($d)){
                        $RmDiagnosis->kode = null;
                        $RmDiagnosis->nama_diagnosis = $value;
                    } else {
                        $RmDiagnosis->kode = $value;
                        $RmDiagnosis->nama_diagnosis = $d['nama'];
                    }
                    $RmDiagnosis->kasus = $RmDiagnosis->kasusBaruLama($id);

                    $berhasil = $berhasil && $RmDiagnosis->save();
                }
            }

            if(isset($post_data['icd_9'])){
                RmTindakanCoding::deleteAll(['rm_id'=>$id]);
                foreach ($post_data['icd_9'] as $key => $value) {
                    $RmTindakanCoding = new RmTindakanCoding();
                    $d = EklaimIcd9cm::findOne($value);
                    $RmTindakanCoding->rm_id = $id;
                    if(empty($d)){
                        $RmTindakanCoding->kode = null;
                        $RmTindakanCoding->long_desc = $value;
                        $RmTindakanCoding->short_desc = $value;
                    } else {
                        $RmTindakanCoding->kode = $value;
                        $RmTindakanCoding->long_desc = $d['long_desc'];
                        $RmTindakanCoding->short_desc = $d['short_desc'];
                    }


                    $berhasil = $berhasil && $RmTindakanCoding->save();
                }
            }

            if($berhasil)
                \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
            else
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                

            return $this->redirect(['index']);
            
        }

        $rm_diagnosis_temp = RmDiagnosis::findAll(['rm_id'=>$id]);
        $rm_diagnosis_id = [];
        $rm_diagnosis_text = [];
        foreach ($rm_diagnosis_temp as $key => $value) {
            $rm_diagnosis_id[$key] = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'];
            $rm_diagnosis_text[$key]  = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'].' - '.$value['nama_diagnosis'];
        }

        $rm_icd9_temp = RmTindakanCoding::findAll(['rm_id'=>$id]);
        $rm_icd9_id = [];
        $rm_icd9_text = [];
        foreach ($rm_icd9_temp as $key => $value) {
            $rm_icd9_id[$key] = empty($value['kode']) ? $value['short_desc'] : $value['kode'];
            $rm_icd9_text[$key]  = empty($value['kode']) ? $value['short_desc'] : $value['kode'].' - '.$value['short_desc'];
        }

        $rm_diagnosis_banding_temp = RmDiagnosisBanding::findAll(['rm_id'=>$id]);

        $rm_diagnosis_banding_id = [];
        $rm_diagnosis_banding_text = [];
        foreach ($rm_diagnosis_banding_temp as $key => $value) {
            $rm_diagnosis_banding_id[$key] = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'];
            $rm_diagnosis_banding_text[$key]  = empty($value['kode']) ? $value['nama_diagnosis'] : $value['kode'].' - '.$value['nama_diagnosis'];
        }
        
        $this->layout = 'main_no_portlet';
        $histori_rm = RekamMedis::findAll(['mr'=>$model->mr]);
        $user_m = Dokter::findOne($model->user_id);
        $rm_diagnosis = RmDiagnosis::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_icd9 = RmTindakanCoding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_diagnosis_banding = RmDiagnosisBanding::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obat = RmObat::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_lab = RmLab::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_rad = RmRad::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_tindakan = RmTindakan::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik = RmObatRacik::find()->where(['rm_id'=>$id])->asArray()->all();
        $rm_obatracik_komponen = [];
        foreach ($rm_obatracik as $key => $value) {
            $rm_obatracik_komponen[$value['racik_id']] = RmObatRacikKomponen::find()->where(['racik_id'=>$value['racik_id']])->asArray()->all();
        }
        $pasien = Pasien::findOne(['mr'=>$model->mr]);
        

        

        $data_penunjang = RmPenunjang::findAll(['rm_id'=>$id]);
        return $this->render('coding', compact('model','rm_diagnosis','rm_icd9','rm_diagnosis_banding','rm_obat','rm_obatracik','rm_obatracik_komponen','rm_tindakan','pasien','data_penunjang','histori_rm','rm_lab','rm_rad','user_m','rm_diagnosis_banding_text','rm_diagnosis_text','rm_diagnosis_id','rm_icd9_text','rm_icd9_id','rm_diagnosis_banding_id','model_kunjungan'));
    }


    /**
     * Finds the RekamMedis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RekamMedis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RekamMedis::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelPenunjang($id)
    {

        if (($model = RmPenunjang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelKunjungan($id)
    {
        if (($model = Kunjungan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function isUserAuthorCreate()
    {
        $kunjungan_id = Yii::$app->security->decryptByKey( utf8_decode(Yii::$app->request->get('kunjungan_id')), Yii::$app->params['kunciInggris'] );

        return $this->findModelKunjungan($kunjungan_id)->klinik_id == Yii::$app->user->identity->klinik_id;
    }

    protected function isUserAuthor()
    {   
        $id = Yii::$app->security->decryptByKey( utf8_decode(Yii::$app->request->get('id')), Yii::$app->params['kunciInggris'] );
        return $this->findModel($id)->user_id == Yii::$app->user->identity->id;
    }

    protected function isUserAuthorPenunjang($rm_id)
    {   
        return $this->findModel($rm_id)->user_id == Yii::$app->user->identity->id;
    }

    
}
