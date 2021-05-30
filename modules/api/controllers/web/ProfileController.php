<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\helpers\UserHelper;
use app\modules\api\models\User;
use app\modules\api\services\UserService;
use Yii;

class ProfileController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\User';

    public function actionIndex()
    {
        $current_user = Yii::$app->user->identity;

        return [
            'code'    => $this->response_code['request_success'],
            'user' => $current_user,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_user = User::findOne($id);

        if (!$edit_user) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $user = UserService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_user);

        if ($user) {
            $user_res = User::findOne($user->id);
            return [
                'code' => $this->response_code['request_success'],
                'user' => $user_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'user' => $user->errors,
        ];
    }

    public function actionCheckCurrentPassword()
    {
    	$current_password_hash = User::findOne(1);
        $request = Yii::$app->request->getBodyParams();
        $current_password = $request['current_password'];

        $check_password = Yii::$app->getSecurity()->validatePassword($current_password, $current_password_hash->encrypted_password);
        if(!$check_password){
        	return [
                'code' => $this->response_code['request_invalid'],
                'current_password' => 'incorrect',
            ];
        }

        return [
                'code' => $this->response_code['request_success'],
                'current_password' => 'correct',
        ];
    }
}
