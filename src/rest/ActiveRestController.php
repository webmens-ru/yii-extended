<?php

namespace wm\yii\rest;

use wm\yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\auth\CompositeAuth;

/**
 * Class ActiveRestController
 * @package wm\admin\controllers
 */
class ActiveRestController extends \yii\rest\ActiveController
{
    public $modelClassSearch;
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:3000'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE', 'GET', 'OPTIONS'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                    'Access-Control-Allow-Origin' => ['*'],
                    'Access-Control-Allow-Headers' => ['*'],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    'xml' => \yii\web\Response::FORMAT_XML,
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                ],
            ],
        ];
    }

    /**
     * @return mixed
     * Возвращается массив actions
     * `index`, `view`, `create`, `update`, `delete`, `options`
     */
    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete" и "create"
        // unset($actions['delete'], $actions['create']);
        // настроить подготовку провайдера данных с помощью метода
        // "prepareDataProvider()"
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['delete']['class'] = 'wm\yii\rest\DeleteAction';
        $actions['data'] = [
            'class' => 'wm\yii\rest\DataAction',
            'modelClass' => $this->modelClass,
            'prepareSearchQuery' => function ($query, $requestParams) {
                $searchModel = new $this->modelClassSearch();
                $query = $searchModel->prepareSearchQuery($query, $requestParams);
                return $query;
            },
//            'auth' => function ()  { //TODO Нужен для запросов в Битрикс
//                $userId = Yii::$app->user->id;
//                $userModel = User::find()->where(['id' => $userId])->one();
//                $auth = $userModel->b24AccessParams;
//                return ArrayHelper::toArray(json_decode($auth));
//            },
            'pagination' => false,
//            'pagination' =>[
//                'pageSize' => 50
//            ],
        ];
        return $actions;
    }

    /**
     * @return mixed
     */
    public function prepareDataProvider()
    {
        $searchModel = new $this->modelClassSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    /**
     * @param null $entity
     * @return mixed
     */
    public function actionSchema($entity = null)
    {
        $model = new $this->modelClass();
        return $model->schema;
    }

    /**
     * @return mixed
     */
    public function actionValidation()
    {
        $model = new $this->modelClass();
        return $model->restRules;
    }

    /**
     * @return mixed
     */
    public function actionGetButtonAdd()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function actionGridActions()
    {
        return null;
    }
}
