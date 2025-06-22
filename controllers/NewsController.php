<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Favorite;
use yii\bootstrap5\Carousel;
use yii\filters\auth\HttpBearerAuth;
use yii\httpclient\Client;
use yii\rest\ActiveController;

class NewsController extends ActiveController
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => [isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://' . $_SERVER['REMOTE_ADDR']],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
            'actions' => [

                'get-favorites' => [
                    'Access-Control-Allow-Creaditials' => true
                ],
                'add-favorite' => [
                    'Access-Control-Allow-Creaditials' => true
                ],
            ]
        ];

        $auth = [
            'class' => HttpBearerAuth::class,
            'only' => ['get-favorites', 'add-favorite']
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
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
                return $this->asJson([
                    'data' => [$response->data],
                    'code' =>    200
                ]);
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

    public function actionGetPopular()
    {
        $apiKey = '0c87da31f05e44f19c723d280085b11e';

        try {

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl("https://newsapi.org/v2/top-headlines?country=us")
                ->setHeaders([
                    'Authorization' => "0c87da31f05e44f19c723d280085b11e",
                    'User-Agent' => 'MyApp/1.0 (http://newsapi)'
                ])
                ->send();
            if ($response->isOk) {
                return $this->asJson([
                    'data' => [$response->data],
                    'code' =>    200
                ]);
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


    // DECLINED
    // public function actionAddFavorite()
    // {
    //     $data = Yii::$app->request->post();
    //     $model = new Favorite();
    //     $model->load($data, '');
    //     $model->user_id = Yii::$app->user->id;
    //     if ($model->validate()) {
    //         $model->save(false);
    //         return $this->asJson([
    //             'message' => 'added to favorites',
    //             'code' => 200
    //         ]);
    //     } else {
    //         return $this->asJson([
    //             'error' => [
    //                 'errors' => $model->errors
    //             ],
    //             'code' => 422
    //         ]);
    //     }
    // }

    // public function actionGetFavorites()
    // {
    //     $arr_of_urls = Favorite::find()
    //         ->where(['user_id' => Yii::$app->user->id])
    //         ->asArray()
    //         ->all();
    //         $str=''
    //     foreach 
    // }

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
