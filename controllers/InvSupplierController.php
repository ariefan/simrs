<?php

namespace app\controllers;

use Yii;
use app\models\InvSupplier;
use app\models\InvSupplierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * InvSupplierController implements the CRUD actions for InvSupplier model.
 */
class InvSupplierController extends Controller
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
     * Lists all InvSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvSupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvSupplier model.
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
     * Creates a new InvSupplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvSupplier();

        if ($model->load(Yii::$app->request->post())) {
            $model->pic=UploadedFile::getInstance($model,'pic');
            if($model->save()&&$model->upload($model->supplier_cd)){
                return $this->redirect(['view', 'id' => $model->supplier_cd]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InvSupplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $pic=$model->pic;

        if ($model->load(Yii::$app->request->post())) {
            $model->pic=UploadedFile::getInstance($model,'pic');
            if(!empty($model->pic)){
                if($model->save()&&$model->upload($model->supplier_cd)){
                    return $this->redirect(['view', 'id' => $model->supplier_cd]);
                }
            }else{
                $model->pic=$pic;
                if($model->save()){
                    return $this->redirect(['view', 'id' => $model->supplier_cd]);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InvSupplier model.
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
     * Finds the InvSupplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return InvSupplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvSupplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
