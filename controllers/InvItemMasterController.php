<?php

namespace app\controllers;

use Yii;
use app\models\InvBatchItem;
use app\models\InvItemMaster;
use app\models\search\InvItemMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvItemMasterController implements the CRUD actions for InvItemMaster model.
 */
class InvItemMasterController extends Controller
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
     * Lists all InvItemMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvItemMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionWarning()
    {
        $expired_warnings = InvBatchItem::findBySql("select
            batch_no, item_cd, item_nm, trx_qty, date(expire_date) expire_date, minimum_stock, maximum_stock,
            datediff(expire_date, now()) days_left,
            IF(expire_date <= DATE_ADD(now(),INTERVAL 15 WEEK), 1, 0) expired_warning,
            IF(trx_qty < minimum_stock, 1, 0) stock_warning
            from inv_batch_item join inv_item_master using(item_cd)
            WHERE 
            expire_date <= DATE_ADD(now(),INTERVAL 15 WEEK) 
            order by item_nm")->asArray()->all();   

        $stock_warnings = InvBatchItem::findBySql("select
            batch_no, item_cd, item_nm, sum(trx_qty) trx_qty, date(expire_date) expire_date, minimum_stock, maximum_stock,
            datediff(expire_date, now()) days_left,
            IF(expire_date <= DATE_ADD(now(),INTERVAL 3 WEEK), 1, 0) expired_warning,
            IF(trx_qty < minimum_stock, 1, 0) stock_warning
            from inv_batch_item join inv_item_master using(item_cd)
            WHERE 
            trx_qty < minimum_stock
            group by item_cd
            order by item_nm")->asArray()->all();        

        return $this->render('warning', [
            'expired_warnings' => $expired_warnings,
            'stock_warnings' => $stock_warnings,
        ]);
    }

    /**
     * Displays a single InvItemMaster model.
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
     * Creates a new InvItemMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvItemMaster();
		$model->item_cd = Yii::$app->getDb()->createCommand("select concat(lpad(year(now()),4,'0'),lpad(month(now()),2,'0'),lpad(day(now()),2,'0'),if(left(item_cd,4) >= '2017', lpad(max(right(item_cd, 6))+1, 6, '0'), '000001')) cd from inv_item_master")->queryAll()[0]['cd'];  
		if ($model->load(Yii::$app->request->post())) {
            $model->last_update = date('Y-m-d H:i:s');
            $model->last_user = Yii::$app->user->identity->username;
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->item_cd]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InvItemMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->last_update = date('Y-m-d H:i:s');
            $model->last_user = Yii::$app->user->identity->username;
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->item_cd]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InvItemMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTambahObat($tipe=null,$counter=null)
    {
        return $this->renderAjax('tambah_obat',['tipe'=>$tipe,'counter'=>$counter]);
    }

    public function actionCariObat()
    {
        $post_data = Yii::$app->request->post();
        $kw = $post_data['keyword'];
        $query = InvItemMaster::findBySql("SELECT 
              item_cd,item_nm,unit_nm,inv_pos_item.`quantity` AS qty,IF(generic_st=1,'Generic','Tidak') AS generic
            FROM
              inv_item_master 
              JOIN inv_unit USING (unit_cd)
              JOIN inv_pos_item USING (item_cd)
            WHERE (LOWER(item_nm) LIKE LOWER('%$kw%')) 
            AND inv_pos_item.pos_cd = 'WHFAR01'"
        );

        return json_encode($query->asArray()->all());
    }

    /**
     * Finds the InvItemMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return InvItemMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvItemMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
