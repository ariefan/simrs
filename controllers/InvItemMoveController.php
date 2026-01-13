<?php

namespace app\controllers;

use Yii;
use app\models\InvBatchItem;
use app\models\InvItemMove;
use app\models\InvPosItem;
use app\models\InvPosInventory;
use app\models\InvItemMoveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * InvItemMoveController implements the CRUD actions for InvItemMove model.
 */
class InvItemMoveController extends Controller
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
     * Lists all InvItemMove models.
     * @return mixed
     */
    public function actionIndex()
    { 
		$request = Yii::$app->request;
        $searchModel = new InvItemMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $request->get('move_tp', 'In'));
      


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'request' => $request,
        ]);
    }

    public function actionIndexOut()
    {
        $searchModel = new InvItemMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Out');

        return $this->render('index_out', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexTransfer()
    {
        $searchModel = new InvItemMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Transfer');

        return $this->render('index_transfer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexKonversi()
    {
        $searchModel = new InvItemMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'Transfer');

        return $this->render('index_konversi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvItemMove model.
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
     * Creates a new InvItemMove model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvItemMove();
        $batch = new InvBatchItem();
		
        if ($model->load(Yii::$app->request->post())) {
            $model->modi_id = (string) Yii::$app->user->identity->id;
            $model->modi_datetime = date('Y-m-d h:i:s');
			$posItem = InvPosItem::find()->where(['pos_cd' => $model->pos_cd, 'item_cd' => $model->item_cd]);
			if($posItem->count() > 0){
				$posItem->quantity += 1;
                $posItem->modi_id = (string) Yii::$app->user->identity->id;
                $posItem->modi_datetime = date('Y-m-d h:i:s');
				$posItem->save();
			}else{
				$model->move_tp = Yii::$app->request->get('move_tp', 'In');
                $batch->load(Yii::$app->request->post());
                $batch->item_cd = $model->item_cd;
                $batch->trx_qty = $model->trx_qty;
                $batch->supplier = $model->vendor;
                $batch->save();
                //var_dump($batch); exit;
			}
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', compact('batch','model'));
        }
    }

    public function actionCreateOut()
    {
        $model = new InvItemMove();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = InvItemMove::getDb()->beginTransaction(); 

            $batch_used = $model->kurangiStok(
                $model->pos_cd,
                $model->item_cd,
                $model->trx_qty,
                $model->note
            );

            if(!empty($batch_used)){
                $transaction->commit();
                \Yii::$app->getSession()->setFlash('success', 'Data Berhasil Disimpan');
                return $this->redirect(['index-out']);
            } else {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->redirect(['create-out']);
            }
        }

        $pos_item = InvPosItem::find()->select('inv_pos_item.pos_cd,pos_nm')->distinct()->joinWith('pos')->asArray()->all();
        
        return $this->render('create_out',compact('model','pos_item'));
    }

    public function actionCreateTransfer()
    {
        $model = new InvItemMove();

        $p = Yii::$app->request->post();

        if ($model->load($p)) {
            if(!empty($model->multi_barang)){
                $transaction = InvItemMove::getDb()->beginTransaction(); 
                $hasil = true;
                foreach ($model->multi_barang as $key => $value) {
                    $hasil = $hasil && $model->transferStok(
                        $model->pos_cd,
                        $model->pos_destination,
                        $value['item_cd'],
                        $value['trx_qty']
                    );
                }

                if($hasil){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Data Berhasil Disimpan');
                    return $this->redirect(['index-transfer']);
                } else {
                    $transaction->rollBack();
                    \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                    return $this->redirect(['create-transfer-multi']);
                }
            }
            
        }

        $pos_item = InvPosItem::find()->select('inv_pos_item.pos_cd,pos_nm')->distinct()->joinWith('pos')->asArray()->all();
        $pos_all = InvPosInventory::find()->asArray()->all();

        $data_item = [];
        if(!empty($p)){
            $data_item_temp = InvPosItem::find()->select('inv_pos_item.item_cd,item_nm,quantity,unit_cd')->joinWith('item')->where(['pos_cd'=>$p['InvItemMove']['pos_cd']])->orderBy('item_nm')->asArray()->all();
            $data_item = [];
            foreach ($data_item_temp as $key => $value) {
                $data_item[$key]['id'] = $value['item_cd'].'|'.$value['quantity'].'|'.$value['unit_cd'];
                $data_item[$key]['name'] = $value['item_cd'].' - '.$value['item_nm'];
            }
        }

        return $this->render('create_transfer_multi',compact('model','pos_item','pos_all','data_item'));
    }

    public function actionCreateKonversi()
    {
        $model = new InvItemMove();
        
        if ($model->load(Yii::$app->request->post())) {
            $transaction = InvItemMove::getDb()->beginTransaction(); 

            $hasil = $model->transferStok(
                $model->pos_cd,
                $model->pos_destination,
                $model->item_cd,
                $model->trx_qty
            );

            if($hasil){
                $transaction->commit();
                \Yii::$app->getSession()->setFlash('success', 'Data Berhasil Disimpan');
                return $this->redirect(['index-konversi']);
            } else {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->redirect(['create-konversi']);
            }
        }

        $pos_item = InvPosItem::find()->select('inv_pos_item.pos_cd,pos_nm')->distinct()->joinWith('pos')->asArray()->all();
        $pos_all = InvPosInventory::find()->asArray()->all();
        
        return $this->render('create_konversi',compact('model','pos_item','pos_all'));
    }

    public function actionPosItem(){
        $post = Yii::$app->request->post();
        $pos_cd = $post['depdrop_parents'][0];
        $pos_item = InvPosItem::find()->select('inv_pos_item.item_cd,item_nm,quantity,unit_cd')->joinWith('item')->where(['pos_cd'=>$pos_cd])->orderBy('item_nm')->asArray()->all();
        $items = [];
        foreach ($pos_item as $key => $value) {
            $items[$key]['id'] = $value['item_cd'].'|'.$value['quantity'].'|'.$value['unit_cd'];
            $items[$key]['name'] = $value['item_cd'].' - '.$value['item_nm'];
        }

        return json_encode(['output'=>$items, 'selected'=>'']);
    }

    /**
     * Updates an existing InvItemMove model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
     * Deletes an existing InvItemMove model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the InvItemMove model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return InvItemMove the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvItemMove::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
