<?php

namespace app\modules\api\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use Yii;

/**
 * Default controller for the `api` module
 */

class DefaultController extends \yii\rest\ActiveController
{
    public $response_code;
    public $constants;

    public function init()
    {
        $this->response_code = Yii::$app->getModule('api')->params['reponse_code'];
        $this->constants     = Yii::$app->getModule('api')->params['constants'];
    }

    public function behaviors()
    {
        $behaviors               = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];

        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

        return $actions;
    }
}
