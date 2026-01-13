<?php

namespace app\controllers;

use Yii;
use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\Klinik;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JadwalController implements the CRUD actions for Jadwal model.
 */
class JadwalController extends Controller
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
     * Lists all Jadwal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JadwalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPerklinik()
    {
        $kliniks = Klinik::find()
            ->orderBy('klinik_nama')
            ->all();
        
        $result = [];
        foreach ($kliniks as $klinik) {
            $result[] = Yii::$app->getDb()->createCommand("
                SELECT 
                  klinik.klinik_nama nama_klinik,
                  dokter.nama nama_dokter,
                  spesialis.nama nama_spesialis,
                  jadwal.medunit_cd,
                  day_tp hari,
                  CONCAT(
                    TIME_FORMAT(time_start, '%H:%S'),
                    ' - ',
                    TIME_FORMAT(time_end, '%H:%S')
                  ) jam 
                FROM
                  klinik_credit 
                  JOIN klinik USING (klinik_id) 
                  JOIN dokter USING (user_id) 
                  LEFT JOIN spesialis ON spesialis.spesialis_id = dokter.spesialis
                  RIGHT JOIN jadwal USING (user_id) 
                ORDER BY klinik_id,
                  user_id,
                  day_tp,
                  time_start ", [':klinik_id' => $klinik->klinik_id])->queryAll();                
        }

        return $this->render('perklinik', [
            'datas' => $result,
        ]);
    }

    public function actionRekap()
    {
        $jadwal = Jadwal::find()->orderBy(['medunit_cd'=>SORT_ASC,'user_id'=>SORT_ASC])->all();
        $data = [];
        foreach ($jadwal as $key => $value) {
          $data[$value->medunitCd->medunit_nm][$value->user->nama][$value->day_tp][] = substr($value->time_start,0,5) .' - ' . substr($value->time_end,0,5); 
        }
        // echo '<pre>';
        // print_r($data);exit;
        return $this->render('rekap_jadwal',compact('data'));
    }

    public function actionPerhari()
    {
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        $result = [];
        foreach ($haris as $hari) {
            $result[] = Yii::$app->getDb()->createCommand("
                SELECT 
                  klinik.klinik_nama nama_klinik,
                  dokter.nama nama_dokter,
                  spesialis.nama nama_spesialis,
                  medunit_cd,
                  day_tp hari,
                  CONCAT(
                    TIME_FORMAT(time_start, '%H:%S'),
                    ' - ',
                    TIME_FORMAT(time_end, '%H:%S')
                  ) jam 
                FROM
                  klinik_credit 
                  JOIN klinik USING (klinik_id) 
                  JOIN dokter USING (user_id) 
                  LEFT JOIN spesialis ON spesialis.spesialis_id = dokter.spesialis
                  RIGHT JOIN jadwal USING (user_id) 
                WHERE day_tp = :day_tp 
                ORDER BY day_tp,
                  time_start,
                  user_id,
                  klinik_id ", [':day_tp' => $hari])->queryAll();                
        }

        return $this->render('perhari', [
            'datas' => $result,
        ]);
    }

    /**
     * Displays a single Jadwal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Jadwal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jadwal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Jadwal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Jadwal model.
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
     * Finds the Jadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jadwal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
