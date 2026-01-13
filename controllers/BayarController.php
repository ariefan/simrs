<?php

namespace app\controllers;

use Yii;
use app\models\RekamMedis;
use app\models\Bayar;
use app\models\BayarObat;
use app\models\BayarTindakan;

use app\models\BayarRad;
use app\models\BayarLab;
use app\models\BayarKelas;
use app\models\BayarGeneral;
use app\models\Ruang;

use app\models\Pasien;
use app\models\Kunjungan;
use app\models\BayarSearch;
use app\models\StokObat;
use app\models\Tindakan;
use app\models\RmTindakan;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;

/**
 * BayarController implements the CRUD actions for Bayar model.
 */
class BayarController extends Controller
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
                           'actions' => ['index','create','delete','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_DOKTER_ADMIN
                           ],
                       ],
                       [
                           'actions' => ['index','create', 'update', 'delete','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_ADMIN,User::ROLE_KASIR
                           ],
                       ]
                   ],
            ],
        ];
    }

    /**
     * Lists all Bayar models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new BayarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bayar model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id,$asal=null)
    {
        $asal = empty($asal) ? 'bayar/index' : $asal; 
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');

        $obat = BayarObat::findAll(['no_invoice'=>$id]);
        $tindakan = BayarTindakan::findAll(['no_invoice'=>$id]);
        $model = $this->findModel($id);
        return $this->renderAjax('view', compact('model','obat','tindakan','asal'));
    }

    /**
     * Creates a new Bayar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id,$asal=null)
    {
        $id = Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris'] );

        $model = Bayar::find()->where(['kunjungan_id'=>$id])->orderBy(['created'=>SORT_ASC]);

        if($model->count()>0)
            $model = $model->one();
        else{
            $model = new Bayar();
            $model->no_invoice = $model->createNoInvoice();
        }
        
        $obat = $model->getBayarObat($id); //
        $obat_racik = $model->getBayarObatRacik($id);//
        $tindakan = $model->getBayarTindakan($id);//
        $radiologi = $model->getBayarRadiologi($id);//
        $lab = $model->getBayarLab($id);//
        $paket = $model->getBayarPaket($id);//
        $ruang = $model->getBayarRuang($id);//

        $kunjungan = Kunjungan::find()->joinWith('mr0')->where(['kunjungan_id'=>$id])->one();
        $transaction = Bayar::getDb()->beginTransaction(); 
        if (isset($_POST['Bayar']) && $model->load(Yii::$app->request->post())) {
            $post_data = Yii::$app->request->post();
            if(isset($post_data['selesai']) && $post_data['selesai']='selesai')
                $selesai = true;
            else
                $selesai = false;
            $model_rm = RekamMedis::findOne(['kunjungan_id'=>$id]);
            
            try{
                $obat = $model->getBayarObat($id,-1); //
                $obat_racik = $model->getBayarObatRacik($id,-1);//
                $tindakan = $model->getBayarTindakan($id,-1);//
                $radiologi = $model->getBayarRadiologi($id,-1);//
                $lab = $model->getBayarLab($id,-1);//
                $paket = $model->getBayarPaket($id,-1);//
                $StokObat = new StokObat();

                $model->kunjungan_id = $id;
                $model->mr = $kunjungan['mr'];
                $model->nama_pasien = $kunjungan['mr0']['nama'];
                $model->alamat = $kunjungan['mr0']['alamat'];
                $model->tanggal_bayar = date('Y-m-d H:i:s');
                $model->subtotal = 0; //Yii::$app->request->post()['subtotal'];
                $model->diskon = 0;
                $model->total += Yii::$app->request->post()['ttl'];
                $model->bayar = str_replace('.', '', $model->bayar);
                $model->kembali = $model->bayar - $model->total;
                $model->insurance_cd = '';//$kunjungan->insurance_cd;
                $model->card_no = '';

                // $model->validate();
                // var_dump($model->getErrors());
                // exit;
                $model->save();

                if($kunjungan['medunit_cd']=="")
                if(isset($post_data['Bayar']['items']['ruangan'][$ruang['seq_no']]) && ($selesai || $post_data['Bayar']['items']['ruangan'][$ruang['seq_no']]=='on')){
                    $BayarKelas = new BayarKelas();
                    $BayarKelas->no_invoice = $model->no_invoice;
                    $BayarKelas->tarif_kelas_id = $ruang['seq_no'];
                    $BayarKelas->nama_kelas = $ruang['ruang_nm'];
                    $BayarKelas->jumlah = $ruang['nHari'];
                    $BayarKelas->harga = $ruang['nHari'] * $ruang['tarif'];
                    
                    // $BayarKelas->validate();
                    // var_dump($BayarKelas->getErrors());

                    $BayarKelas->save();
                }

                //OK
                foreach ($obat as $key => $value) 
                if(isset($post_data['Bayar']['items']['obatNon'][$value['obat_id']]) && ($selesai || $post_data['Bayar']['items']['obatNon'][$value['obat_id']]=='on'))
                {
                    $BayarObat = new BayarObat();
                    $BayarObat->no_invoice = $model->no_invoice;
                    $BayarObat->obat_id = $value['obat_id'];
                    $BayarObat->nama_obat = $value['nama_merk'];
                    $BayarObat->jumlah = $value['jumlah'];
                    $BayarObat->harga_satuan = $value['harga_jual'];
                    $BayarObat->harga_total = $value['jumlah'] * $value['harga_jual'];
                    $BayarObat->is_racik = 0;

                    // echo $value['obat_id'];
                    // $BayarObat->validate();
                    // var_dump($BayarObat->getErrors());
                    // exit;

                    $BayarObat->save();
                }

                //OK
                foreach ($obat_racik as $key => $value)
                if(isset($post_data['Bayar']['items']['obat'][$value['obat_id']]) && ($selesai || $post_data['Bayar']['items']['obat'][$value['obat_id']]=='on')){
                    $BayarObat = new BayarObat();
                    $BayarObat->no_invoice = $model->no_invoice;
                    $BayarObat->obat_id = $value['obat_id'];
                    $BayarObat->nama_obat = $value['nama_merk'];
                    $BayarObat->jumlah = $value['jumlah'];
                    $BayarObat->harga_satuan = $value['harga_jual'];
                    $BayarObat->harga_total = $value['jumlah'] * $value['harga_jual'];
                    $BayarObat->is_racik = 1;
                    $BayarObat->save();
                }

                //OK
                foreach ($tindakan as $key => $value)
                if(isset($post_data['Bayar']['items']['tindakan'][$value['tarif_id']]) && ($selesai || $post_data['Bayar']['items']['tindakan'][$value['tarif_id']]=='on'))
                {
                    $BayarTindakan = new BayarTindakan();
                    $BayarTindakan->no_invoice = $model->no_invoice;
                    $BayarTindakan->tindakan_id = $value['tindakan_id'];
                    $BayarTindakan->nama_tindakan = $value['nama_tindakan'];
                    $BayarTindakan->harga = $value['tarif'];
                    $BayarTindakan->jumlah = $value['jumlah'];
                    $BayarTindakan->save();

                    // $BayarTindakan->validate();
                    // var_dump($BayarTindakan->getErrors());
                }

                // echo '<pre>';
                // print_r($radiologi);
                // print_r($post_data['Bayar']['items']['rad']);
                // OK;
                foreach ($radiologi as $key => $value)
                if(isset($post_data['Bayar']['items']['rad'][$value['medicalunit_id']]) && ($selesai || $post_data['Bayar']['items']['rad'][$value['medicalunit_id']]=='on'))
                {
                    $bayarRad = new BayarRad();
                    $bayarRad->no_invoice = $model->no_invoice;
                    $bayarRad->rad_cd =$value['medicalunit_id'];
                    $bayarRad->nama_rad =$value['nama_radio'];
                    $bayarRad->harga =$value['tarif'];
                    $bayarRad->jumlah = $value['jumlah'];

                    // $bayarRad->validate();
                    // var_dump($bayarRad->getErrors());

                    $bayarRad->save();

                }

                //OK
                foreach ($lab as $key => $value) 
                if(isset($post_data['Bayar']['items']['lab'][$value['medicalunit_cd']]) && ($selesai || $post_data['Bayar']['items']['lab'][$value['medicalunit_cd']]=='on'))
                {
                    $bayarLab = new BayarLab();
                    $bayarLab->no_invoice = $model->no_invoice;
                    $bayarLab->lab_cd = $value['medicalunit_cd'];
                    $bayarLab->nama_lab = $value['nama_lab'];
                    $bayarLab->harga = $value['tarif'];
                    $bayarLab->jumlah = $value['jumlah'];
                    $bayarLab->save();
                }

                // OK
                foreach ($paket as $key => $value)
                if(isset($post_data['Bayar']['items']['umum'][$value['tarif_general_id']]) && ($selesai || $post_data['Bayar']['items']['umum'][$value['tarif_general_id']]=='on'))
                {
                    $bayarPaket = new BayarGeneral();
                    $bayarPaket->no_invoice = $model->no_invoice;
                    $bayarPaket->tarif_general_id = $value['tarif_general_id'];
                    $bayarPaket->nama_tarif = $value['nama_paket'];
                    $bayarPaket->tarif = $value['tarif'];
                    $bayarPaket->jumlah = 1;
                    $bayarPaket->total = $bayarPaket->tarif;
                    $bayarPaket->save();
                }

                $model_kunjungan = $this->findModelKunjungan($model->kunjungan_id);
                if($selesai){
                    $model_kunjungan->status = 'selesai';
                    $model_kunjungan->save();
                    $model->is_complete = 1;
                    $model->save();

                    if(!empty($model_kunjungan->ruang_cd)){
                        $model_ruang = Ruang::findOne($model_kunjungan->ruang_cd);
                        $model_ruang->status = 0;
                        $model_ruang->save();

                    }
                }

                $rm = RekamMedis::findAll(['kunjungan_id'=>$model->kunjungan_id]);
                foreach ($rm as $rm_val) {
                    $model_rm = $this->findModelRm($rm_val['rm_id']);
                    $model_rm->locked = 1;
                    $model_rm->save();
                }

                $transaction->commit();
                \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
                if(!empty($asal)) return $this->redirect([$asal]);
                return $this->redirect(Yii::$app->request->referrer);

            }  catch(\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->render('create', compact('ruang','model','obat','obat_racik','tindakan','kunjungan', 'radiologi','lab','paket'));
            }
            

            return $this->redirect(['view', 'id' => $model->no_invoice]);
        } else {
            return $this->render('create', compact('ruang','model','obat','obat_racik','tindakan','kunjungan', 'radiologi','lab','paket'));
        }
    }

    /**
     * Updates an existing Bayar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->no_invoice]);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetTindakan()
    {
        $post_data = Yii::$app->request->post();
        $data = Tindakan::findBySql("SELECT * FROM tindakan WHERE tindakan_id IN (".implode(',', $post_data['ids']).")")->asArray()->all();
        return $this->renderAjax('tindakan',compact('data'));
    }

    /**
     * Deletes an existing Bayar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id,$asal)
    {
        if(!$this->isUserAuthor()) 
            throw new NotFoundHttpException('The requested page does not exist.');

        $model_bayar = $this->findModel($id);
        
        $model_kunjungan = $this->findModelKunjungan($model_bayar->kunjungan_id);
        $model_kunjungan->status = 'antri bayar';
        $model_kunjungan->save();

        $model_rm = RekamMedis::findOne(['kunjungan_id'=>$model_bayar->kunjungan_id]);
        $model_rm->locked = 0;
        $model_rm->save();

        BayarObat::deleteAll(['no_invoice'=>$id]);
        BayarTindakan::deleteAll(['no_invoice'=>$id]);
        $model_bayar->delete();
        if(empty($asal)){
            return $this->redirect(['bayar/index']);
        } else {
            return $this->redirect([$asal]);
        }
        
    }

    /**
     * Finds the Bayar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Bayar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bayar::findOne($id)) !== null) {
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

    protected function findModelKunjungan($id)
    {
        if (($model = Kunjungan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function isUserAuthor()
    {   
        $model_bayar = $this->findModel(Yii::$app->request->get('id'));
        return $this->findModelKunjungan($model_bayar->kunjungan_id)->klinik_id == Yii::$app->user->identity->klinik_id;
    }
}
