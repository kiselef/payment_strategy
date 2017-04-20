<?php

namespace app\controllers;

use app\components\Payer;
use app\components\Test1Strategy;
use app\components\Test2Strategy;
use app\models\User;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PaymentController extends Controller
{
    /**
     * Yii2 CSRF validation.
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * Страница для провайдера 1.
     *
     * @return string
     */
    public function actionTest1()
    {
        \Yii::$app->getResponse()->format = Response::FORMAT_RAW;
        \Yii::$app->getResponse()->headers->add('Content-Type', 'text/xml; charset=utf-8');

        $strategy = new Test1Strategy();
        $strategy->user_id = \Yii::$app->getRequest()->get('a');
        $strategy->amount = \Yii::$app->getRequest()->get('b');
        $strategy->md5 = \Yii::$app->getRequest()->get('md5');

        $payer = new Payer($strategy);
        $response = $payer->pay();

        return $this->renderPartial('payment_result', compact('response'));
    }

    /**
     * Страница для провайдера 2.
     *
     * @return string
     */
    public function actionTest2()
    {
        \Yii::$app->getResponse()->format = Response::FORMAT_RAW;
        
        $strategy = new Test2Strategy();
        $strategy->user_id = \Yii::$app->getRequest()->post('x');
        $strategy->amount = \Yii::$app->getRequest()->post('y');
        $strategy->md5 = \Yii::$app->getRequest()->post('md5');

        $payer = new Payer($strategy);
        $response = $payer->pay();

        return $this->renderPartial('payment_result', compact('response'));
    }

    /**
     * Страница списка транзакций пользователя.
     *
     * @param $name
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTransactions($name)
    {
        $user = User::find()
            ->where(['name' => $name])
            ->with('transactions')
            ->asArray()
            ->one();

        if (empty($user)) {
            throw new NotFoundHttpException();
        }

        return $this->renderPartial('transactions', compact('user'));
    }
}
