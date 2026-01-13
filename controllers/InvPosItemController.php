<?php

namespace app\controllers;

use Yii;
use app\models\InvItemMove;
use app\models\InvPosItem;
use app\models\InvBatchItem;
use app\models\InvPosItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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

    public function actionIndexPenyesuaian()
    {
        $searchModel = new InvPosItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_penyesuaian', [
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
        $batch = InvBatchItem::find()->where(['item_cd'=>$item_cd,'pos_cd'=>$pos_cd]);
        $move = InvItemMove::find()->joinWith('posCd a')->joinWith('posDestination b')->where(['inv_item_move.pos_cd'=>$pos_cd,'item_cd'=>$item_cd]);
        $d = InvPosItem::find()->joinWith('item')->joinWith('pos')->where(['inv_pos_item.pos_cd'=>$pos_cd,'inv_pos_item.item_cd'=>$item_cd])->one();
        $dataMove = new ActiveDataProvider([
            'query' => $move,
        ]);
        $dataBatch = new ActiveDataProvider([
            'query' => $batch,
        ]);
        $model = $this->findModel($pos_cd, $item_cd);
        return $this->render('view', compact('dataMove','model','d','dataBatch'));
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
        $d = InvPosItem::find()->joinWith('item')->joinWith('pos')->where(['inv_pos_item.pos_cd'=>$pos_cd,'inv_pos_item.item_cd'=>$item_cd])->one();
        $model->modi_id = Yii::$app->user->identity->username;
        $model->modi_datetime = date('Y-m-d h:i:s');
        $old_qty = $model->quantity;
        if ($model->load(Yii::$app->request->post())) {
            $p = Yii::$app->request->post();
            $move = new InvItemMove();
            $move->sesuaikanStok($pos_cd,$item_cd,$old_qty,$model->quantity,$p['catatan']);
            $model->save();
            return $this->redirect(['view', 'pos_cd' => $model->pos_cd, 'item_cd' => $model->item_cd]);
        } else {
            return $this->render('update', compact('model','d'));
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
