<?php

namespace app\controllers;

use Yii;
use app\models\Region;
use app\models\RegionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends Controller
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
     * Lists all Region models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Region model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGetarea(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $root = $parents[0];
                $out = Region::findBySql("SELECT region_cd AS id,region_nm AS name FROM region WHERE region_root = $root")->asArray()->all(); 
                
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    /**
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Region();
        $post_data = Yii::$app->request->post();
        if (!empty($post_data)) {
            $model->load($post_data);
            if($model->region_level==1){
                $model->region_root = "";
                $temp = Region::findBySql("SELECT MAX(region_cd)+1 AS kode FROM region WHERE region_level=1")->asArray()->all();
                if($temp[0]['kode'] != null){
                    $model->region_cd = $temp[0]['kode'];
                }else{
                     $model->region_cd = $id."01";
                }
            } else if($model->region_level==2){
                $id=$model->region_root;
                $temp = Region::findBySql("SELECT MAX(region_cd)+1 AS kode FROM region WHERE region_level=2 AND region_root=".$id)->asArray()->all();
                if($temp[0]['kode'] != null){
                    $model->region_cd = $temp[0]['kode'];
                }else{
                     $model->region_cd = $id."01";
                }
            } else if($model->region_level==3){
                $id=$model->region_root;
                $temp = Region::findBySql("SELECT MAX(region_cd)+1 AS kode FROM region WHERE region_level=3 AND region_root=".$id)->asArray()->all();
                if($temp[0]['kode'] != null){
                    $model->region_cd = $temp[0]['kode'];
                }else{
                     $model->region_cd = $id."01";
                }
                
            } else if($model->region_level==4){
                $id=$model->region_root;
                $temp = Region::findBySql("SELECT MAX(region_cd)+1 AS kode FROM region WHERE region_level=4 AND region_root=".$id)->asArray()->all();
                if($temp[0]['kode'] != null){
                    $model->region_cd = $temp[0]['kode'];
                }else{
                     $model->region_cd = $id."01";
                }
            }

            $model->modi_id = Yii::$app->user->identity->username;
            $model->modi_datetime = date("Y/m/d H:i:s");
            $model->save();

            return $this->redirect(['view', 'id' => $model->region_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->modi_id = Yii::$app->user->identity->username;
            $model->modi_datetime = date("Y/m/d H:i:s");
            $model->save();

            return $this->redirect(['view', 'id' => $model->region_cd]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    //public function actionDelete($id)
    //{
    //    $this->findModel($id)->delete();

    //    return $this->redirect(['index']);
    //}

    public function actionLists($id)
    {
        $countPosts = Region::find()->count();
        $posts = Region::find()->orderBy('region_cd ASC')->all();
        if($countPosts>0){
            foreach($posts as $post){
                if($post->region_level==1 && $post->region_level<$id){
                    echo "<option value='".$post->region_cd."'>"."Provinsi ".$post->region_nm."</option>";
                }else if($post->region_level==2 && $post->region_level<$id){
                    echo "<option value='".$post->region_cd."'>"."<==>Kab./Kota ".$post->region_nm."</option>";
                }else if($post->region_level==3 && $post->region_level<$id){
                    echo "<option value='".$post->region_cd."'>"."<=====>Kec. ".$post->region_nm."</option>";
                }else if($post->region_level==4 && $post->region_level<$id){
                    echo "<option value='".$post->region_cd."'>"."<==========>Kel.".$post->region_nm."</option>";
                }
                
            }
        }
        else{
            echo "<option>-</option>";
        }
 
    }

    public function actionLists2() {
        $post = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $sub_id = $parents[0];
                //$out = Region::find()->orderBy('region_cd ASC')->asArray()->all();
                $posts = Region::find()->select('region_cd as id,region_nm as name')->where(['region_level'=>$sub_id-1])->asArray()->all();
                /*foreach($posts as $post){
                if($post->region_level==1 && $post->region_level<$sub_id){
                    echo "<option value='".$post->region_cd."'>"."Provinsi ".$post->region_nm."</option>";
                }else if($post->region_level==2 && $post->region_level<$sub_id){
                    echo "<option value='".$post->region_cd."'>"."<==>Kab./Kota ".$post->region_nm."</option>";
                }else if($post->region_level==3 && $post->region_level<$sub_id){
                    echo "<option value='".$post->region_cd."'>"."<=====>Kec. ".$post->region_nm."</option>";
                }else if($post->region_level==4 && $post->region_level<$sub_id){
                    echo "<option value='".$post->region_cd."'>"."<==========>Kel.".$post->region_nm."</option>";
                }
                }*/
                echo json_encode(['output'=>$posts, 'selected'=>'']);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }

    /*public function actionBuatKodeRegion($id){
        $_left = $id;
        $_first = "01";
        
        $larik = (ArrayHelper::map(Region::find()->where(['region_root' => $id])->all(), 'region_cd', 'region_cd'));
        if(is_null($larik[end($larik)])){
            $no = $_left . $_first;
        }else{
            $no = $larik[end($larik)]+1;
        }
        $this->kode_region_baru = $no;
        //echo($kode_region_baru);
        return $no;
        //return;
    }*/

    /**
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Region the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
