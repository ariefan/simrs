<?php

namespace app\controllers;

use Yii;
use app\models\InvPosItem;
use app\models\InvPosItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvPosItemController implements the CRUD actions for InvPosItem model.
 */
class InvPosItemController extends Controller
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
     * Lists all InvPosItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvPosItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvPosItem model.
     * @param string $pos_cd
     * @param string $item_cd
     * @return mixed
     */
    public function actionView($pos_cd, $item_cd)
    {
        return $this->render('view', [
            'model' => $this->findModel($pos_cd, $item_cd),
        ]);
    }

    /**
     * Creates a new InvPosItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvPosItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pos_cd' => $model->pos_cd, 'item_cd' => $model->item_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InvPosItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $pos_cd
     * @param string $item_cd
     * @return mixed
     */
    public function actionUpdate($pos_cd, $item_cd)
    {
        $model = $this->findModel($pos_cd, $item_cd);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pos_cd' => $model->pos_cd, 'item_cd' => $model->item_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InvPosItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $pos_cd
     * @param string $item_cd
     * @return mixed
     */
    public function actionDelete($pos_cd, $item_cd)
    {
        $this->findModel($pos_cd, $item_cd)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InvPosItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $pos_cd
     * @param string $item_cd
     * @return InvPosItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pos_cd, $item_cd)
    {
        if (($model = InvPosItem::findOne(['pos_cd' => $pos_cd, 'item_cd' => $item_cd])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
