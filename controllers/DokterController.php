<?php

namespace app\controllers;

use Yii;
use app\models\Dokter;
use app\models\Klinik;
use app\models\RefKokab;
use app\models\DokterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\User;
use yii\web\UploadedFile;

/**
 * DokterController implements the CRUD actions for Dokter model.
 */
class DokterController extends Controller
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
                   'only' => ['index','create', 'update', 'delete'],
                   'rules' => [
                       [
                           'actions' => ['index','create', 'update', 'delete','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_ADMIN
                           ],
                       ],
                       [
                           'actions' => ['update','view'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_DOKTER,User::ROLE_DOKTER_ADMIN
                           ],
                           
                       ]
                   ],
            ],
        ];
    }

    /**
     * Lists all Dokter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DokterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dokter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id=null)
    {
        $id = empty($id) ? Yii::$app->user->identity->id : $id;
        if(Yii::$app->user->identity->role!=10)
            if(!$this->isUserAuthor($id))
                throw new NotFoundHttpException('Terjadi Kesalahan.');
            
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model_klinik' => $this->findModelKlinik(Yii::$app->user->identity->klinik_id),
        ]);
    }

    /**
     * Creates a new Dokter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dokter();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if(isset($model->imageFile)){
                $model->upload();
                $src = 'img/dokter/' . $model->user_id;
                $ext = $model->imageFile->extension;
                $model->foto = "$src.$ext";
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dokter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->identity->role!=10) 
            if(!$this->isUserAuthor($id)) 
                throw new NotFoundHttpException('The requested page does not exist.');
            

        $model = $this->findModel($id);
        $model->scenario = Dokter::SCENARIO_PROFILE;
        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if(isset($model->imageFile)){
                $model->upload();
                $src = 'img/dokter/' . $model->user_id;
                $ext = $model->imageFile->extension;
                $model->foto = "$src.$ext";
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateKlinik($id)
    {
        if(Yii::$app->user->identity->role!=10) 
            if(!$this->isUserAuthor($id))
                throw new NotFoundHttpException('The requested page does not exist.');

        $model = $this->findModelKlinik(Yii::$app->user->identity->klinik_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
            return $this->redirect(['view','id'=>$id]);
        } else {
            return $this->render('update_klinik', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dokter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionKokab() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $sub_id = $parents[0];
                $out = RefKokab::find()->select('kota_id as id,kokab_nama as name')->where(['provinsi_id'=>$sub_id])->asArray()->all();
                //print_r($out);exit;
                echo json_encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    /**
     * Finds the Dokter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dokter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dokter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelKlinik($id)
    {
        if (($model = Klinik::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Tidak ada Klinik.');
        }
    }

    protected function isUserAuthor($id)
    {   
        return $this->findModel($id)->user_id == Yii::$app->user->identity->id;
    }
}
