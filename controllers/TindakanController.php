<?php

namespace app\controllers;

use Yii;
use app\models\Tindakan;
use app\models\search\TindakanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * TindakanController implements the CRUD actions for Tindakan model.
 */
class TindakanController extends Controller
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
     * Lists all Tindakan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TindakanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCari($q=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $connection = Yii::$app->db;
        if (!is_null($q)) {
            $sql = "SELECT 
                      a.`tindakan_cd` AS id,
                      CONCAT(tindakan_cd,' - ',a.`nama_tindakan`) AS text 
                    FROM
                      tindakan AS a 
                    WHERE a.nama_tindakan LIKE '%$q%' OR a.tindakan_cd LIKE '%$q%'";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => TindakanController::find($id)->nama];
        }

        return $out;
    }

    public function actionIcd9($q=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $connection = Yii::$app->db;
        if (!is_null($q)) {
            $sql = "SELECT 
                      id,
                      CONCAT(id,' - ',short_desc) text 
                    FROM
                      eklaim_icd9cm
                    WHERE short_desc LIKE '%$q%' OR id LIKE '%$q%'";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            
            $out['results'] = array_values($data);
        }

        return $out;
    }

    /**
     * Displays a single Tindakan model.
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
     * Creates a new Tindakan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tindakan();
        $model->klinik_id = 109;
        if ($model->load(Yii::$app->request->post())) {
            $model->created = date('Y-m-d H:i:s');
            // $model->modi_id = Yii::$app->user->identity->username;
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->tindakan_cd]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tindakan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tindakan_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tindakan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tindakan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tindakan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tindakan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
