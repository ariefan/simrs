<?php

namespace app\controllers;

use Yii;
use app\models\GiziDiet;
use app\models\search\RmInapGiziSearch;
use app\models\GiziDietSearch;
use app\models\GiziMakanan;
use app\models\RmInapGizi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GiziDietController implements the CRUD actions for GiziDiet model.
 */
class GiziDietController extends Controller
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

    public function actionCari($q=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $connection = Yii::$app->db;
        if (!is_null($q)) {
            $sql = "SELECT a.`kode` AS id, a.`nama_diet` AS text 
                FROM gizi_diet AS a 
                    WHERE a.kode LIKE '%$q%' OR a.nama_diet LIKE '%$q%'";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => TindakanController::find($id)->nama];
        }

        return $out;
    }

    /**
     * Lists all GiziDiet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GiziDietSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPermintaan(){
        $searchModel = new RmInapGiziSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('permintaan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionRekapPermintaan()
    {
         $post_data = Yii::$app->request->post();
        if(!empty($post_data)){
          $connection = Yii::$app->db;

          $tgl_awal = $post_data['tgl_awal'];
          $tgl_akhir = $post_data['tgl_akhir'];
//             $tgl_awal = '2017-10-01';
//            $tgl_akhir = '2018-04-01';
          $sql = "Select rm_inap_gizi.rm_id as 'No Rekam Medis', pasien.nama as 'Pasien', kunjungan.ruang_cd as 'Ruangan', gizi_diet.nama_diet as 'Diet', rm_inap_gizi.jam_makan as 'Jam Makan ', rm_inap_gizi.jam_makan_spesifik, rm_inap_gizi.status, rm_inap_gizi.diagnosa, rm_inap_gizi.created
            from `rm_inap_gizi`
            LEFT JOIN `rekam_medis` on rm_inap_gizi.rm_id=rekam_medis.rm_id
            LEFT JOIN `pasien` on rekam_medis.mr=pasien.mr
            LEFT JOIN `kunjungan` on rekam_medis.kunjungan_id=kunjungan.kunjungan_id
            LEFT JOIN `gizi_diet` on rm_inap_gizi.kode_diet=gizi_diet.kode
            WHERE tanggal_periksa BETWEEN '$tgl_awal' 
                    AND '$tgl_akhir' 
            order by rm_inap_gizi.rm_id;";
          $command = $connection->createCommand($sql);
          $data = $command->queryAll();

          $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'Rekap Permintaan Gizi' => [   // Name of the excel sheet
                    'data' => $data,
                    // Set to `false` to suppress the title row
                    'titles' => [
                        'No Rekam Medis',
                        'Pasien',  
                        'Ruangan', 
                        'Diet' ,
                        'Jam Makan',
                        'Jam Spesifik',
                        'Diagnosa',
                        'Status',
                        'Create',
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
          $file->send("Rekap Permintaan Gizi $tgl_awal s/d $tgl_akhir.xlsx"); 

        } else {
          return $this->render('rekap_permintaan_gizi');
        }
    }
    



    /**
     * Displays a single GiziDiet model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GiziDiet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GiziDiet();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $post = Yii::$app->request->post();
            GiziMakanan::deleteAll(['kode_diet' => $model->kode]);
            if(isset($post['makanan']))
                    if(!empty($post['makanan']))
                        foreach ($post['makanan'] as $makanan) {
                            $makanan['kode_diet'] = $model->kode;
                            $makanan_model = new GiziMakanan();
                            $makanan_model->attributes = $makanan;
                            $makanan_model->save();
                        }
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionTambahMakanan()
    {
        return $this->renderAjax('tambah_makanan');
    }

    public function actionCariMakanan()
    {
        $post_data = Yii::$app->request->post();
        //var_dump(Yii::$app->request);exit;
        $q = $post_data['q'];
        $query = GiziMakanan::findBySql("SELECT * FROM gizi_makanan 
            WHERE (LOWER(bahan_makanan) LIKE LOWER('%$q%'))"
        );

        return json_encode($query->asArray()->all());
    }

    /**
     * Updates an existing GiziDiet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $post = Yii::$app->request->post();
            GiziMakanan::deleteAll(['kode_diet' => $model->kode]);
            if(isset($post['makanan']))
                    if(!empty($post['makanan']))
                        foreach ($post['makanan'] as $makanan) {
                            $makanan['kode_diet'] = $model->kode;
                            $makanan_model = new GiziMakanan();
                            $makanan_model->attributes = $makanan;
                            $makanan_model->save();
                        }
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GiziDiet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTerproses($id){
        $model = $this->findModelRm($id);
        $model->status = 'Sudah Diproses';
        if($model->save())
            \Yii::$app->getSession()->setFlash('success', 'Berhasil diproses...');
        $this->redirect(['gizi-diet/permintaan']);
    }

    public function actionBatalTerproses($id){
        $model = $this->findModelRm($id);
        $model->status = 'Belum Diproses';
        if($model->save())
            \Yii::$app->getSession()->setFlash('success', 'Berhasil diproses...');
        $this->redirect(['gizi-diet/permintaan']);
    }

    /**
     * Finds the GiziDiet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GiziDiet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GiziDiet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModelRm($id)
    {
        if (($model = RmInapGizi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}