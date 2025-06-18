<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\bootstrap5\Carousel;
use yii\httpclient\Client;

class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionGetNews($query = 'news', $page = 1)
    {
        $queryEncoded = rawurlencode($query);
        $apiKey = '0c87da31f05e44f19c723d280085b11e';

        try {

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl("https://newsapi.org/v2/everything?q=$queryEncoded&pageSize=5&page=$page")
                ->setHeaders([
                    'Authorization' => "0c87da31f05e44f19c723d280085b11e",
                    'User-Agent' => 'MyApp/1.0 (http://newsapi)'
                ])
                ->send();
            if ($response->isOk) {
                var_dump($response->data['articles']);
                die;
            } else {
                var_dump($response);
                die;
                Yii::$app->response->statusCode = 500;
                return $this->asJson([
                    'error' => 'server has problems',
                ]);
            }
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500; // Internal Server Error
            return $this->asJson([
                'error' => 'Internal server error',
                'details' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
