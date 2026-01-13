<?php

namespace app\controllers;

use Yii;
use app\models\RlGrouping310;
use app\models\RlGrouping310Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RlGrouping310Controller implements the CRUD actions for RlGrouping310 model.
 */
class RlGrouping310Controller extends Controller
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
     * Lists all RlGrouping310 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RlGrouping310Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RlGrouping310 model.
     * @param string $rl_ref_310_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionView($rl_ref_310_no, $tindakan_cd)
    {
        return $this->render('view', [
            'model' => $this->findModel($rl_ref_310_no, $tindakan_cd),
        ]);
    }

    /**
     * Creates a new RlGrouping310 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RlGrouping310();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rl_ref_310_no' => $model->rl_ref_310_no, 'tindakan_cd' => $model->tindakan_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RlGrouping310 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $rl_ref_310_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionUpdate($rl_ref_310_no, $tindakan_cd)
    {
        $model = $this->findModel($rl_ref_310_no, $tindakan_cd);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rl_ref_310_no' => $model->rl_ref_310_no, 'tindakan_cd' => $model->tindakan_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RlGrouping310 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $rl_ref_310_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionDelete($rl_ref_310_no, $tindakan_cd)
    {
        $this->findModel($rl_ref_310_no, $tindakan_cd)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RlGrouping310 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $rl_ref_310_no
     * @param string $tindakan_cd
     * @return RlGrouping310 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($rl_ref_310_no, $tindakan_cd)
    {
        if (($model = RlGrouping310::findOne(['rl_ref_310_no' => $rl_ref_310_no, 'tindakan_cd' => $tindakan_cd])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
