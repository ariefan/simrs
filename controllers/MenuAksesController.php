<?php

namespace app\controllers;

use Yii;
use app\models\MenuAkses;
use app\models\MenuAksesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuAksesController implements the CRUD actions for MenuAkses model.
 */
class MenuAksesController extends Controller
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
     * Lists all MenuAkses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuAksesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuAkses model.
     * @param integer $menu_id
     * @param integer $role
     * @return mixed
     */
    public function actionView($menu_id, $role)
    {
        return $this->render('view', [
            'model' => $this->findModel($menu_id, $role),
        ]);
    }

    /**
     * Creates a new MenuAkses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuAkses();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'menu_id' => $model->menu_id, 'role' => $model->role]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuAkses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $menu_id
     * @param integer $role
     * @return mixed
     */
    public function actionUpdate($menu_id, $role)
    {
        $model = $this->findModel($menu_id, $role);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'menu_id' => $model->menu_id, 'role' => $model->role]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MenuAkses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $menu_id
     * @param integer $role
     * @return mixed
     */
    public function actionDelete($menu_id, $role)
    {
        $this->findModel($menu_id, $role)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MenuAkses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $menu_id
     * @param integer $role
     * @return MenuAkses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($menu_id, $role)
    {
        if (($model = MenuAkses::findOne(['menu_id' => $menu_id, 'role' => $role])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
