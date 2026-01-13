<?php

namespace app\controllers;

use Yii;
use app\models\RmLabNapzaDetail;
use app\models\JenisPeriksaLab;
use app\models\RmLabNapza;
use app\models\RmLabNapzaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;


/**
 * RmLabNapzaController implements the CRUD actions for RmLabNapza model.
 */
class RmLabNapzaController extends Controller
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
            'access' => [
                   'class' => AccessControl::className(),
                   'ruleConfig' => [
                       'class' => AccessRule::className(),
                   ],
                   'rules' => [
                       [
                           'actions' => ['index', 'view', 'create', 'delete','cetak','update'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_LAB
                           ],
                       ],
                   ],
            ],
        ];
    }

    /**
     * Lists all RmLabNapza models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new RmLabNapzaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RmLabNapza model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCetak($id)
    {
        return $this->renderPartial('cetak', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RmLabNapza model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $id =Yii::$app->security->decryptByKey( utf8_decode($id), Yii::$app->params['kunciInggris']);
        $model = new RmLabNapza();
        $model->rm_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach (JenisPeriksaLab::find()->all() as $key => $value) {
                $det = new RmLabNapzaDetail;
                $det->lab_napza_id = $model->lab_napza_id;
                $det->periksa_id = $value->periksa_id;
                $det->hasil = (in_array($value->periksa_id, $model->hasils))? "1":"0";
                $det->save();
            }
            return $this->redirect(['view', 'id' => $model->lab_napza_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RmLabNapza model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach (JenisPeriksaLab::find()->all() as $key => $value) {
                $det = RmLabNapzaDetail::find()->where(['periksa_id'=>$value->periksa_id,'lab_napza_id'=>$model->lab_napza_id])->one();
                $det->hasil = (in_array($value->periksa_id, $model->hasils))? "1":"0";
                $det->save();
            }
            return $this->redirect(['view', 'id' => $model->lab_napza_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RmLabNapza model.
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
     * Finds the RmLabNapza model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RmLabNapza the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RmLabNapza::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
