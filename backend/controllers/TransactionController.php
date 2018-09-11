<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Invoice;
use common\models\Transaction;
use common\models\TransactionSearch;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['viewTransactions'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists the user's transaction models.
     * @return mixed
     */
    public function actionUser($id)
    {
        if (!$user = User::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere([
            'user_id' => $id,
        ]);

        return $this->render('user', [
            'user' => $user,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists the invoice transaction models.
     * @return mixed
     */
    public function actionInvoice($id)
    {
        if (!$invoice = Invoice::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere([
            'invoice_id' => $id,
        ]);

        return $this->render('invoice', [
            'invoice' => $invoice,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transaction();

        $model->attachBehavior('stamp', [
            'class' => \yii\behaviors\TimestampBehavior::className(),
            'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['stamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['stamp'],
            ],
            'value' => date('Y-m-d H:i:s'),
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
