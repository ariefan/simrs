<?php

namespace app\controllers;

use Yii;
use app\models\Pasien;
use app\models\RmLab;
use app\models\UnitMedisItem;
use app\models\RmLabSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Kunjungan;
use app\models\KunjunganSearch;

use app\models\RekamMedis;
use app\models\Dokter;
use kartik\mpdf\Pdf;

/**
 * RmLabController implements the CRUD actions for RmLab model.
 */
class RmLabController extends Controller
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
        ];
    }

    /**
     * Lists all RmLab models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KunjunganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        // $post_data = Yii::$app->request->post();
        // $tanggal = date('Y-m-d');
        // if(!empty($post_data)){
        //     $tanggal = $post_data['tanggal'];
        // }

        // $model = new RmLab();
        // $data = $model->getDataLab($tanggal);
        // return $this->render('index',compact('data','tanggal'));
    }

    

    /**
     * Creates a new RmLab model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = $this->findModelKunjungan($id);
        $rm = RekamMedis::find()->where(['kunjungan_id'=>$id])->asArray()->one();
        $pasien = Pasien::findOne($model->mr);

        $data_lab = [];
        $rm_id = '';
        $dokter = '';
        if(!empty($rm)){
            $data_lab = RmLab::find()->joinWith('jenis')->where(['rm_id'=>$rm['rm_id']])/*->asArray()*/->all();
            $rm_id = $rm['rm_id'];
            if(!empty($data_lab))
                $dokter = $data_lab[0]['dokter'];
        }

        $rm_lab = [];
        foreach ($data_lab as $key => $value) {
            $rm_lab[$key] = $value['medicalunit_cd'];
        }
        $data_dokter = Dokter::find()->joinWith('user')->where(['role'=>'20'])->orWhere(['role'=>'25'])->asArray()->all();
        $item_lab = UnitMedisItem::getWithSub('LAB00');
        $post_data = Yii::$app->request->post();

        if (!empty($post_data)) {
            // echo '<pre>';
            // print_r($post_data); die;
            if(empty($rm)){
                $model_rm = new RekamMedis();
                $model_rm->mr = $model->mr;
                $model_rm->user_id = Yii::$app->user->identity->id;
                $model_rm->kunjungan_id = $id;
                $model_rm->tgl_periksa = date('Y-m-d');
                $model_rm->created = date('Y-m-d H:i:s');
                $model_rm->save();

                $rm_id = $model_rm->rm_id;
            }
            if(isset($post_data['tindakan']['listTindakan'])){
                $ada = [];

                foreach ($post_data['tindakan']['listTindakan'] as $key => $value) {
                    $ada[]=$value;
                    $RmLab = new RmLab();
                    $ex = RmLab::find()->where(['rm_id'=>$rm_id,'medicalunit_cd'=>$value])->asArray()->one();
                    if(!empty($ex))
                        continue;
                    $RmLab->rm_id = $rm_id;
                    $RmLab->medicalunit_cd = $value;
                    $d = Dokter::findOne($post_data['tindakan']['listDokter'][$key]);
                    $RmLab->dokter = $post_data['tindakan']['listDokter'][$key];
                    $RmLab->jumlah = $post_data['tindakan']['listJumlah'][$key];
                    $RmLab->dokter_nama = $d->nama;
                    $d = UnitMedisItem::findOne($value);
                    $RmLab->nama = $d['medicalunit_nm'];
                    $RmLab->save();
                }

                foreach ($rm_lab as $key => $medicalunit_cd) {
                    if(!in_array($medicalunit_cd, $ada)){
                        RmLab::deleteAll("rm_id=$rm_id AND medicalunit_cd='$medicalunit_cd'");
                    }
                }
            } else {
                RmLab::deleteAll("rm_id=$rm_id");
            }
                

            if(isset($post_data['catatan']))
                foreach ($post_data['catatan'] as $id => $catatan) {
                    $m = $this->findModel($id);
                    if(!empty($m)){
                        $m->catatan = $catatan;
                        $m->hasil = $post_data['hasil'][$id];
                        // print_r($_FILES);exit;
                        if($_FILES["gambar"]['name'][$id]){
                            $directory = Yii::getAlias('@app/web/rm_penunjang/');
                            $dir = Yii::getAlias('@web/rm_penunjang/');

                            $img = $_FILES["gambar"]["name"][$id]; //stores the original filename from the client
                            $tmp = $_FILES["gambar"]["tmp_name"][$id]; //stores the name of the designated temporary file
                            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                            $uid = uniqid(time(), true);
                            $fileName = $uid . '.' . $ext;
                            $filePath = $directory . $fileName;

                            move_uploaded_file($tmp,$filePath);

                            $m->hasil_file = $fileName;
                        }
                        $m->save(); 
                    }
                    
                }

            if(isset($post_data['Simpan'])){
                return $this->redirect(['create','id'=>$model->kunjungan_id]);
            } else {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', compact('model','item_lab','pasien','data_dokter','rm_lab','data_lab','dokter'));
        
    }

    // public function actionUpdate($id)
    // {
    //     $model = $this->findModelRm($id);
    //     $pasien = Pasien::findOne($model->mr);
    //     $data_lab = RmLab::find()->joinWith('jenis')->where(['rm_id'=>$id])->asArray()->all();
    //     $dokter = $data_lab[0]['dokter'];
    //     $data_dokter = Dokter::find()->asArray()->all();
    //     $post_data = Yii::$app->request->post();
    //     if (!empty($post_data)) {
    //         foreach ($post_data['catatan'] as $id => $catatan) {
    //             $d = Dokter::findOne($post_data['dokter']);
    //             $m = $this->findModel($id);
    //             $m->catatan = $catatan;
    //             $m->hasil = $post_data['hasil'][$id];
    //             $m->dokter = $post_data['dokter'];
    //             $m->dokter_nama = $d->nama;
    //             $m->save();
    //         }
            
    //         return $this->redirect(['index']);
    //     }
    //     return $this->render('update', compact('model','data_lab','pasien','data_dokter','dokter'));
    // }

    public function actionPasiendiproses()
    {
        $SQL = "SELECT DISTINCT a.id, a.rm_id as 'No Rekam Medis',b.nama as 'Nama Pasien',c.tanggal_periksa as 'Tanggal Periksa',d.medunit_nm as 'Unit Pengirim',f.nama as 'Dokter Pengirim',e.medicalunit_nm, a.catatan as 'Catatan', a.hasil as 'Hasil', a.dokter_nama as 'Dokter Pemeriksa',a.nama as 'Nama Pengecekan' FROM rm_lab a, pasien b, kunjungan c, unit_medis d, unit_medis_item e, dokter f, rekam_medis g WHERE (a.rm_id=g.rm_id) and (g.kunjungan_id=c.kunjungan_id) and (g.user_id=f.user_id) and (g.mr=b.mr) and (d.medunit_cd=c.medunit_cd) and (e.medicalunit_cd=a.medicalunit_cd) and (a.catatan IS not NULL or a.hasil IS not NULL) GROUP BY a.id ";

        $daftarPasien2 = RmLab::findBySql($SQL)->asArray()->all();

        return $this->render('pasiendiproses',compact('daftarPasien2'));
    }
    /**
     * Displays a single RmLab model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $SQL = "SELECT DISTINCT a.id as id, a.rm_id as 'No Rekam Medis',b.nama as 'Nama Pasien',c.tanggal_periksa as 'Tanggal Periksa',d.medunit_nm as 'Unit Pengirim',f.nama as 'Dokter Pengirim',e.medicalunit_nm FROM rm_lab a, pasien b, kunjungan c, unit_medis d, unit_medis_item e, dokter f, rekam_medis g WHERE (a.rm_id=g.rm_id) and (g.kunjungan_id=c.kunjungan_id) and (g.user_id=f.user_id) and (g.mr=b.mr) and (d.medunit_cd=c.medunit_cd) and (e.medicalunit_cd=a.medicalunit_cd) and (a.id=$id) GROUP BY a.id ";
        $daftarPasien = RmLab::findBySql($SQL)->asArray()->all();

        return $this->render('view',compact('model','daftarPasien'));
    }

    public function actionUnduh($id)
    {
        $this->layout = 'main_no_portlet';
        $model = $this->findModel($id);
        //$SQL = "Select * from rm_lab where id=$id";
        $SQL = "SELECT DISTINCT a.id, a.rm_id as 'No Rekam Medis',b.nama as 'Nama Pasien',c.tanggal_periksa as 'Tanggal Periksa',d.medunit_nm as 'Unit Pengirim',f.nama as 'Dokter Pengirim',e.medicalunit_nm, a.catatan as 'Catatan', a.hasil as 'Hasil' FROM rm_lab a, pasien b, kunjungan c, unit_medis d, unit_medis_item e, dokter f, rekam_medis g WHERE (a.rm_id=g.rm_id) and (g.kunjungan_id=c.kunjungan_id) and (g.user_id=f.user_id) and (g.mr=b.mr) and (d.medunit_cd=c.medunit_cd) and (e.medicalunit_cd=a.medicalunit_cd) and (a.id=$id) GROUP BY a.id ";        
        $hasil = RmLab::findBySql($SQL)->asArray()->all();

        $content = $this->render('unduh',compact('model','hasil'));
        
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
            'filename' => "Lab".$model->medicalunit_cd."_idPeriksa".$model->id."_"."NoRM ".$model->rm_id.".pdf",
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => "Pemeriksaan Laboratorium ".$model->id." ".$model->rm_id],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>["ID Periksa, No.RM: ".$model->id.",".$model->rm_id], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    /**
     * Updates an existing RmLab model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    

    /**
     * Deletes an existing RmLab model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    //public function actionDelete($id)
    //{
    //    $this->findModel($id)->delete();

    //    return $this->redirect(['index']);
    //}

    /**
     * Finds the RmLab model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RmLab the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RmLab::findOne($id)) !== null) {
            return $model;
        } else {
            return null;
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


    protected function findModelRm($id)
    {
        if (($model = RekamMedis::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
}
