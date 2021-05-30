<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\helpers\UserHelper;
use app\modules\api\models\User;
use Yii;
use yii\data\ActiveDataProvider;

class SessionController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        array_push($behaviors['authenticator']['except'], 'create');

        return $behaviors;
    }

    public function actionCreate()
    {
        $email    = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        $provider = new ActiveDataProvider([
            'query' => User::find()
                ->where(['email' => $email])->asArray()->one(),
        ]);

        $result = $provider->query;

        if ($result) {
            if ($result['email_verified_at'] == null) {
                return [
                    'code'    => $this->response_code['request_invalid'],
                    'message' => 'Your email is not verified',
                ];
            }

            if (Yii::$app->getSecurity()->validatePassword($password, $result['encrypted_password'])) {
                $token    = UserHelper::generateToken($result['id']);
                $response = [
                    'code'         => $this->response_code['request_success'],
                    'access_token' => (string) $token,
                ];
            } else {
                $response = [
                    'code'    => $this->response_code['request_invalid'],
                    'message' => 'Invalid email or password.',
                ];
            }
        } else {
            $response = [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Invalid email or password.',
            ];
        }

        return $response;
    }
}
