<?php

namespace app\controllers;

use app\models\File;
use app\models\User;
use FFI;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\UploadedFile;

class UserController extends \yii\rest\ActiveController
{
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
                'logout' => [
                    'Access-Control-Allow-Credentials' => true,
                ]
            ]
        ];

        $auth = [
            'class' => HttpBearerAuth::class,
            'only' => ['logout']
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
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRegister()
    {
        $model = new User();
        $model->load(Yii::$app->request->post(), '');
        $model->scenario = 'register';
        $model->file = UploadedFile::getInstanceByName('avatar');
        if ($model->validate()) {

            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->save(false);
            if ($model->file) {
                $file = new File();
                $file->title = $model->upload();
                $file->user_id = $model->id;
                $file->save(false);
            }
            Yii::$app->response->statusCode = 201;
            return $this->asJson([
                'message' => 'user created',
                'code' => 201
            ]);
        } else {
            Yii::$app->response->statusCode = 422;
            return $this->asJson([
                'error' => [
                    'message' => 'Validation error',
                    'errors' => $model->errors
                ],
                'code' => 422
            ]);
        }
    }
    public function actionLogin()
    {
        $model = new User();
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate()) {
            $user = User::findOne(['email' => $model->email]);
            if ($user && $user->validatePassword($model->password)) {
                $user->token = Yii::$app->security->generateRandomString();
                $user->save(false);
                return $this->asJson([
                    'data' => [
                        'token' => $user->token
                    ],
                    'code' => 200
                ]);
            } else {
                Yii::$app->response->statusCode = 401;
                return '';
            }
        } else {
            Yii::$app->response->statusCode = 422;
            return $this->asJson([
                'error' => [
                    'message' => 'Validation error',
                    'errors' => $model->errors
                ],
                'code' => 422
            ]);
        }
    }
    public function actionLogout()
    {
        $user = Yii::$app->user->identity;
        $user->token = null;
        $user->save(false);
        Yii::$app->response->statusCode = 204;
        return '';
    }
}
