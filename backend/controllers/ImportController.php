<?php

namespace backend\controllers;

use Yii;
use backend\models\Import;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

/**
 * ImportController description
 */
class ImportController extends Controller
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
                        'roles' => ['importTransactions'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 
     */
    public function actionIndex()
    {
        $model = new Import();

        if ($model->load(Yii::$app->request->post())) {
            $model->process();
            if ($model->getErrorsCount()) {
                \Yii::$app->getSession()->setFlash('error', 'There are ' . $model->getErrorsCount() . ' errors');
            }
            if ($model->getSuccessCount()) {
                \Yii::$app->getSession()->setFlash('success', $model->getSuccessCount() . ' records were imported');
            }
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
