<?php

namespace app\controllers;

use Yii;
use app\models\Kunjungan;
use app\models\Ruang;
use app\models\Sensus;
use app\models\KunjunganSearch;
use app\models\KunjunganNapzaSearch;
use app\models\CreateRanapSearch;

use app\models\RmDiagnosis;
use app\models\RmDiagnosisBanding;
use app\models\RmObat;
use app\models\RmObatRacik;
use app\models\RmObatRacikKomponen;
use app\models\RmTindakan;
use app\models\RekamMedis;
use app\models\Pasien;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;

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
                           'actions' => ['mutasi', 'index','create', 'create2', 'update', 'delete','view','pemeriksaan','bayar','process','cari-pasien'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_ADMIN,User::ROLE_DOKTER_ADMIN,
                           ],
                       ],
                       [
                           'actions' => ['mutasi','create-ranap','index','create', 'update', 'delete','view','cari-pasien'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_PENDAFTARAN,
                           ],
                       ],
                       [
                           'actions' => ['pemeriksaan','view','mutasi'],
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
                           'actions' => ['farmasi','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_FARMASI
                           ],
                       ],
                       [
                           'actions' => ['tracking','view','ketemu','batal-ketemu','kirim','batal-kirim','kembali','batal-kembali'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_RM
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

    /**
     * Lists all Kunjungan models.
     * @return mixed
     */
    public function actionIndex($jenis=null)
    {
        if($jenis=='null') $jenis = 'rj';
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,false,false,'antri',$jenis);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis' => $jenis
        ]);
    }

    public function actionSensus(){
        $dataProvider = new ActiveDataProvider([
            'query' => Sensus::find(),
        ]);

        return $this->render('sensus', compact('dataProvider'));

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true,false,'');

        return $this->render('pemeriksaan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFarmasi()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true,true,'');
        
        return $this->render('farmasi', [
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true,true,'');

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
            $model->status_rm = 'Datang';
            $model->save();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menambahkan ke Antrian');

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('create', [
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
                return $this->redirect([$asal]);
            }
            return $this->redirect(['index']);
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
            return $this->redirect(['view', 'id' => $model->kunjungan_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMutasi($id){
        $model = new Kunjungan();
        $model->scenario = 'mutasi';
        if ($model->load(Yii::$app->request->post())) {
            $mutasi = $model->mutasiTo($id);
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
        return $this->renderAjax('mutasi',['model'=>$model]);
    }

    public function actionProcess($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
