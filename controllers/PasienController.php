<?php

namespace app\controllers;

use Yii;
use app\models\Pasien;
use app\models\PasienSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Region;
use app\models\Kunjungan;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Refsuku;
use linslin\yii2\curl;

/**
 * PasienController implements the CRUD actions for Pasien model.
 */
class PasienController extends Controller
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
     * Lists all Pasien models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PasienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pasien model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewAjax($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDukcapil()
    {
        $post_data = Yii::$app->request->post();

        //API URL
        $url = 'http://localhost:8080/dukcapil/get_json/demo/biodata';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'nik' => $post_data['nik'],
            'user_id' => 'demo',
            'password' => 'demo123'
        );
        $payload = json_encode($data);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $response = curl_exec($ch);

        //close cURL resource
        curl_close($ch);

        return json_encode($response);
    }


    /**
     * Creates a new Pasien model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pasien();

        if ($model->load(Yii::$app->request->post())) {
    	    if(Pasien::find()->where(['mr'=>$model->mr])->count()>0)           
                $model->mr = $model->generateKode_Pasien();

            //$model->mr = $model->generateKode_Pasien();
            $model->suku = strtoupper($model->suku);
            $model->pekerjaan = strtoupper($model->pekerjaan);
            $model->save();
            $model->file = UploadedFile::getInstance($model, 'file');

            if(isset($model->file)){
            	$model->simpanFoto();
            	$src = 'img/pasien/'.$model->mr;
            	$ext = $model->file->extension;
                //save the path in the db column
                $model->foto = "$src.$ext";
            }

            $model->created = date("Y/m/d H:i:s");
            $model->user_input = Yii::$app->user->identity->username;
            $model->klinik_id = '109';
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Pasien berhasil disimpan dengan RM: '.$model->mr);
                return $this->redirect(['view', 'id' => $model->mr]);
            }
            else
                \Yii::$app->getSession()->setFlash('error', 'Gagal Menyimpan pasien.');
        }
        $model->mr = $model->generateKode_Pasien();
        $model->region_cd = '64034';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreate2()
    {
        return $this->redirect(['pasien/create']);
        $model = new Pasien();
        if ($model->load(Yii::$app->request->post())) {
            if(Pasien::find()->where(['mr'=>$model->mr])->count()>0)           
                $model->mr = $model->generateKode_Pasien();
                
            $model->file = UploadedFile::getInstance($model, 'file');

            if(isset($model->file)){
                $model->simpanFoto();
                $src = 'img/pasien/'.$model->mr;
                $ext = $model->file->extension;
                //save the path in the db column
                $model->foto = "$src.$ext";
            }

            $model->created = date("Y/m/d H:i:s");
            $model->user_input = Yii::$app->user->identity->username;
            $model->klinik_id = '109';
            $model->save();

            //langsung masukkan data baru ke kunjungan
            return $this->redirect(['kunjungan/index']);
            //akhir masukkan data baru ke kunjungan
        } else {
            $model->region_cd = '64034';
            $model->mr = $model->generateKode_Pasien();
            return $this->render('create', [
                'model' => $model,
            ]);

        }
    
    }

    /**
     * Updates an existing Pasien model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->suku = strtoupper($model->suku);
            $model->pekerjaan = strtoupper($model->pekerjaan);
            $model->save();

            $model->file = UploadedFile::getInstance($model, 'file');

            if(isset($model->file)){
            	$model->simpanFoto();
            	$src = 'img/pasien/'.$model->mr;
            	$ext = $model->file->extension;
                //save the path in the db column
                $model->foto = "$src.$ext";
            }

            $model->created = date("Y/m/d H:i:s");
            $model->user_input = Yii::$app->user->identity->username;
            $model->klinik_id = '109';
            $model->save();
            
            return $this->redirect(['view', 'id' => $model->mr]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKunjungan($id,$tgl1=null,$tgl2=null)
    {
        if($tgl1 == null || $tgl2 == null){
            $SQL = "SELECT x.kunjungan_id, x.mr, x.tanggal_periksa, x.tipe_kunjungan, x.nama, x.alamat, x.tanggal_lahir, x.jk, x.gol_darah, x.ruang_cd, y.ruang_nm from (SELECT a.kunjungan_id, a.mr, a.tanggal_periksa, a.tipe_kunjungan, b.nama, b.alamat, b.tanggal_lahir, b.jk, b.gol_darah, a.ruang_cd FROM kunjungan a, pasien b where a.mr = $id and a.mr = b.mr) as x 
                left join ruang y on x.ruang_cd=y.ruang_cd group by x.kunjungan_id order by x.tanggal_periksa desc ";
        }else{
            $SQL = "SELECT x.kunjungan_id, x.mr, x.tanggal_periksa, x.tipe_kunjungan, x.nama, x.alamat, x.tanggal_lahir, x.jk, x.gol_darah, x.ruang_cd, y.ruang_nm from (SELECT a.kunjungan_id, a.mr, a.tanggal_periksa, a.tipe_kunjungan, b.nama, b.alamat, b.tanggal_lahir, b.jk, b.gol_darah, a.ruang_cd FROM kunjungan a, pasien b where a.mr = $id and a.mr = b.mr and
                (a.tanggal_periksa>=date($tgl1) and a.tanggal_periksa<=date($tgl2))) as x 
                left join ruang y on x.ruang_cd=y.ruang_cd group by x.kunjungan_id order by x.tanggal_periksa desc ";           
        }
        $rekap_kunjungan = Kunjungan::findBySql($SQL)->asArray()->all();
        return $this->render('kunjungan_pasien',compact('rekap_kunjungan'));

    }

    public function actionKartu($id)
    {
        $this->layout = 'main_no_portlet';
        $model = $this->findModel($id);
        $content = $this->render('kartu',compact('model'));
        
        // setup kartik\mpdf\Pdf component
        //$pdf = new Pdf('utf-8', array(190,236));
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            // 'format' => Pdf::FORMAT_CUSTOM, 
            // 'format' => ['utf-8', array(190,236)],
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'filename' => "KartuPasien".$model->mr.".pdf",
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:8px}', 
             // set mPDF properties on the fly
            'options' => ['title' => "Kartu Pasien".$model->nama],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>["No Pasien, Nama Pasien: ".$model->mr.",".$model->nama], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionCreatempdf(){
            $mpdf=new mPDF();
            $mpdf->WriteHTML($this->renderPartial('kartu'));
            $mpdf->Output();
            exit;
            //return $this->renderPartial('mpdf');
        }

    public function actionKartupdf($id) {
        $model = $this->findModel($id);

        $mpdf = new mPDF(); 
        $mpdf->AddPage();
        $mpdf->SetFont('Arial','',8);

        $mpdf->Cell(20,20,'LOGO', 1,0,'C');
        $mpdf->Cell(60,20,"RUMAH SAKIT UMUM DAERAH (RSUD) BERAU", 1,0,'C');
        $mpdf->Cell(20,20,'BARCODE', 1,1,'C');

        $mpdf->Cell(20,7,'  NO. PASIEN', 'L',0);
        $mpdf->Cell(80,7,'   '.$model->mr, 'R',1);

        $mpdf->Cell(20,7,'  NAMA', 'L',0);
        $mpdf->Cell(80,7,'   '.$model->nama, 'R',1);

        $mpdf->Cell(20,7,'  ALAMAT', 'L',0);
        $mpdf->Cell(80,7,'   '.$model->alamat, 'R',1);

        $mpdf->Cell(20,7,'  TGL. LAHIR', 'LB',0);
        $mpdf->Cell(80,7,'   '.$model->tanggal_lahir, 'RB',1);

        $mpdf->SetFont('Arial','I',6);
        $mpdf->Cell(100,7,'Kartu ini dibuat tanggal: '.date("Y/m/d H:i:s"), 1,0,'C');

        $mpdf->Output($id.'.pdf','D');
        exit;
    }

    public function actionForceDownloadPdf(){
        $mpdf=new mPDF();
        $mpdf->WriteHTML($this->renderPartial('kartu'));
        $mpdf->Output('MyPDF.pdf', 'D');
        exit;
    }

    // Privacy statement output demo
    public function actionMpdfDemo1() {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $this->renderPartial('privacy'),
            'options' => [
                'title' => 'Privacy Policy - Krajee.com',
                'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
            ],
            'methods' => [
                'SetHeader' => ['Generated By: Krajee Pdf Component||Generated On: ' . date("r")],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionPdfreport() {
        // Your SQL query here
        $content = $this->renderPartial('report', ['model' => $model]); 
        // setup kartik\mpdf\Pdf component

         $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Sistem Informasi Akademik'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Sistem Informasi Akademik'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        /*------------------------------------*/
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        /*------------------------------------*/
        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Deletes an existing Pasien model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)->delete();
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menghapus Pasien');
        } catch(\Exception $e) {
            \Yii::$app->getSession()->setFlash('error', 'Gagal Menghapus Pasien, Karena Sudah Memiliki Data Transaksi');
        }

        return $this->redirect(['index']);
    }

    public function actionKokab() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $sub_id = $parents[0];
                $out = Region::find()->select('region_cd as id,region_nm as name')->where(['region_root'=>$sub_id])->asArray()->all();
                echo json_encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public function actionKecamatan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $subcat_id = empty($ids[1]) ? null : $ids[1];
            if ($subcat_id != null) {
                    $out = Region::find()->select('region_cd as id,region_nm as name')->where(['region_root'=>$subcat_id])->asArray()->all();
                    echo json_encode(['output'=>$out, 'selected'=>'']);
                    return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public function actionKelurahan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $subcat_id = empty($ids[2]) ? null : $ids[2];
            if ($subcat_id != null) {
                    $out = Region::find()->select('region_cd as id,region_nm as name')->where(['region_root'=>$subcat_id])->asArray()->all();
                    echo json_encode(['output'=>$out, 'selected'=>'']);
                    return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    public function actionReport() {
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('kartu');
     
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Krajee Report Header'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
     
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    /**
     * Finds the Pasien model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Pasien the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pasien::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
