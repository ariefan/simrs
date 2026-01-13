<?php

namespace app\controllers;

use Yii;
use app\models\bridging\Eklaim;
use app\models\KunjunganEklaim;
use app\models\KunjunganEklaimSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;

/**
 * KunjunganEklaimController implements the CRUD actions for KunjunganEklaim model.
 */
class KunjunganEklaimController extends Controller
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
                           'actions' => ['push','update-all','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_KASIR,
                           ],
                       ],
                       [
                           'actions' => ['update'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_PENDAFTARAN,
                           ],
                       ],
                   ],
            ],
        ];
    }
    /**
     * Lists all KunjunganEklaim models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KunjunganEklaimSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KunjunganEklaim model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KunjunganEklaim model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KunjunganEklaim();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kunjungan_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing KunjunganEklaim model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'pendaftaran';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->upgrade_class_class!='')
                $model->upgrade_class_ind = 1; else $model->upgrade_class_ind = 0;
            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan data');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateAll($id,$subtotal=0)
    {
        $model = $this->findModel($id);
        $model->scenario = 'pendaftaran';

        if ($model->upgrade_class_class!='')
            $model->upgrade_class_ind = 1; else $model->upgrade_class_ind = 0;

        if($model->upgrade_class_ind==1){
            if($model->kunjungan->tipe_kunjungan=='Rawat Jalan')
                $model->upgrade_class_los = 1;
            
            if($model->kunjungan->tipe_kunjungan=='Rawat Inap')
                $model->upgrade_class_los = ($model->kunjungan->bayar0!= null)?$model->kunjungan->bayar0->getBayarRuang($model->kunjungan->kunjungan_id)['nHari']:'1';
        }

        $model->tarif_rs = $subtotal;


        if ($model->load(Yii::$app->request->post())) {


            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan data');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('updateAll', [
                'model' => $model,
            ]);
        }
    }

    public function actionPush($id,$grouping='auto'){
        $model = $this->findModel($id);
        if ($model->isComplete && ($model->status==null || $model->status=='')){
            $metadata = ['method'=>'new_claim'];
            $data = [
                    'nomor_kartu'=>$model->kunjungan->mr0->no_asuransi,
                    'nomor_sep'=>$model->nomor_sep,
                    'nomor_rm'=>$model->kunjungan->mr,
                    'nama_pasien'=>$model->kunjungan->mr0->nama,
                    'tgl_lahir'=>$model->kunjungan->mr0->tanggal_lahir.' 00:00:00',
                    'gender'=>($model->kunjungan->mr0->jk == 'Perempuan')? '2':'1',
                ];
            $eklaim = new Eklaim($metadata, $data);
            $response = $eklaim->execute();
            // echo '1<pre>'; print_r($response);
            if ($response->metadata->code==200){
                $model->status = 'new_claim';
                $model->save();
                $metadata = ["method"=> "set_claim_data", "nomor_sep"=> $model->nomor_sep];
                $data = [
                        "nomor_sep"=> (string) $model->nomor_sep,
                        "nomor_kartu"=> (string) $model->kunjungan->mr0->no_asuransi,
                        "tgl_masuk"=>  (string) $model->kunjungan->jam_masuk,
                        "tgl_pulang"=> (string) $model->kunjungan->jam_selesai,
                        "jenis_rawat"=> ($model->kunjungan->tipe_kunjungan == 'Rawat Jalan')? '2':'1',
                        "kelas_rawat"=> (string) $model->kelas_rawat,
                        "adl_sub_acute"=> (string) $model->adl_sub_acute,
                        "adl_chronic"=> (string) $model->adl_chronic,
                        "icu_indikator"=>(string) $model->icu_indikator,
                        "icu_los"=> (string) $model->icu_los,
                        "ventilator_hour"=> (string) $model->ventilator_hour,
                        "upgrade_class_ind"=> (string) $model->upgrade_class_ind,
                        "upgrade_class_class"=> (string) $model->upgrade_class_class,
                        "upgrade_class_los"=> (string) $model->upgrade_class_los,
                        "add_payment_pct"=> (string) $model->add_payment_pct,
                        "birth_weight"=> (string) $model->birth_weight,
                        "discharge_status"=> (string) $model->discharge_status,
                        "diagnosa"=>  /*"S71.0#A00.1",//*/(string) $model->diagnosasHash,
                        "procedure"=>  /*"81.52#88.38",//*/(string) $model->proceduresHash,
                        "tarif_rs"=> (string) $model->tarif_rs,
                        "tarif_poli_eks"=>(string) $model->tarif_poli_eks,
                        "nama_dokter"=> (string) @$model->kunjungan->rekmed->dokter0->nama,
                        "kode_tarif"=> (string) Eklaim::$kodeTarifDf,//parent
                        "payor_id"=> (string) Eklaim::payor_id($model->payor_id),
                        "payor_cd"=> (string) Eklaim::payor_cd($model->payor_id),
                        "cob_cd"=> (string) $model->cob_cd,
                        "coder_nik"=> (string) Eklaim::$coder_nik//parent
                    ];
                    
                $eklaim2 = new Eklaim($metadata, $data);
                $response2 = $eklaim2->execute();
                if ($response2->metadata->code==200){
                    $model->status = 'set_claim_data';
                    $model->save();

                    if ($grouping=='auto'){
                        $metadata =[ "method"=>"grouper", "stage"=>"1"];
                        $data = ["nomor_sep"=>(string) $model->nomor_sep];
                        $eklaim3 = new Eklaim($metadata, $data);
                        $response3 = $eklaim3->execute();
                        echo '<pre>'; print_r($response3);
                        if ($response3->metadata->code==200){
                            $model->status = 'grouper_1';
                            $model->save();
                            if(isset($response3->special_cmg_option))
                            {
                                $metadata =[ "method"=>"grouper", "stage"=>"2"];
                                $data = ["nomor_sep"=>(string) $model->nomor_sep];
                                $eklaim4 = new Eklaim($metadata, $data);
                                $response4 = $eklaim4->execute();
                                if ($response3->metadata->code==200){
                                    $model->status = 'grouper_2';
                                    $model->save();
                                }
                            }
                        }

                    }
                }
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        else{
            \Yii::$app->getSession()->setFlash('error', 'Gagal Mengirim data ke Eklaim');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing KunjunganEklaim model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KunjunganEklaim model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KunjunganEklaim the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KunjunganEklaim::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
