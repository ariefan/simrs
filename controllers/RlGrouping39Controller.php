<?php

namespace app\controllers;

use Yii;
use app\models\RlGrouping39;
use app\models\RlGrouping39Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RlGrouping39Controller implements the CRUD actions for RlGrouping39 model.
 */
class RlGrouping39Controller extends Controller
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
     * Lists all RlGrouping39 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RlGrouping39Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RlGrouping39 model.
     * @param string $rl_ref_39_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionView($rl_ref_39_no, $tindakan_cd)
    {
        return $this->render('view', [
            'model' => $this->findModel($rl_ref_39_no, $tindakan_cd),
        ]);
    }

    /**
     * Creates a new RlGrouping39 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RlGrouping39();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rl_ref_39_no' => $model->rl_ref_39_no, 'tindakan_cd' => $model->tindakan_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RlGrouping39 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $rl_ref_39_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionUpdate($rl_ref_39_no, $tindakan_cd)
    {
        $model = $this->findModel($rl_ref_39_no, $tindakan_cd);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rl_ref_39_no' => $model->rl_ref_39_no, 'tindakan_cd' => $model->tindakan_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RlGrouping39 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $rl_ref_39_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionDelete($rl_ref_39_no, $tindakan_cd)
    {
        $this->findModel($rl_ref_39_no, $tindakan_cd)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RlGrouping39 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $rl_ref_39_no
     * @param string $tindakan_cd
     * @return RlGrouping39 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($rl_ref_39_no, $tindakan_cd)
    {
        if (($model = RlGrouping39::findOne(['rl_ref_39_no' => $rl_ref_39_no, 'tindakan_cd' => $tindakan_cd])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
