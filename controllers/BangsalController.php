<?php

namespace app\controllers;

use Yii;
use app\models\Bangsal;
use app\models\BangsalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BangsalController implements the CRUD actions for Bangsal model.
 */
class BangsalController extends Controller
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
     * Lists all Bangsal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BangsalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bangsal model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionAvailable()
    {
        $kelases = Yii::$app->getDb()->createCommand("
            select 
            bangsal_cd, bangsal_nm,
            kelas_cd, kelas_nm,
            ruang_cd, ruang_nm
            from ruang
            join bangsal using(bangsal_cd)
            join kelas using(kelas_cd)
            group by bangsal_cd, kelas_cd
            order by bangsal_cd, kelas_cd")->queryAll(); 

        $result = [];
        foreach ($kelases as $kelas) {
            $result[] = Yii::$app->getDb()->createCommand("
                select 
                bangsal_cd, bangsal_nm,
                kelas_cd, kelas_nm,
                ruang_cd, ruang_nm,
                `status`
                from ruang
                join bangsal using(bangsal_cd)
                join kelas using(kelas_cd)
                where bangsal_cd = :bangsal_cd and kelas_cd = :kelas_cd
                order by bangsal_cd, kelas_cd, ruang_cd", 
                [':bangsal_cd' => $kelas['bangsal_cd'], ':kelas_cd' => $kelas['kelas_cd']])->queryAll();                
        }

        return $this->render('available', [
            'datas' => $result,
        ]);
    }

    /**
     * Creates a new Bangsal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bangsal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bangsal_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bangsal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bangsal_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bangsal model.
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
     * Finds the Bangsal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Bangsal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bangsal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
