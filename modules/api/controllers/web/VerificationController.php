<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\helpers\UserHelper;
use app\modules\api\models\User;
use app\modules\api\models\UserProperty;
use Yii;
use yii\db\Expression;

class VerificationController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        array_push($behaviors['authenticator']['except'], 'index');

        return $behaviors;
    }

    public function actionIndex()
    {
        $data              = Yii::$app->request->get();
        $is_accepted_email = false;
        $token             = 'confirmation_token';

        if ($data['property_id']) {
            $verified_data = UserProperty::find()->where([
                'property_id'   => $data['property_id'],
                'invited_token' => $data['token'],
            ])->one();
            $is_accepted_email = true;
            $token             = 'invited_token';
        } else {
            $verified_data = User::find()->where([
                'confirmation_token' => $data['token'],
            ])->one();
        }

        if (!$verified_data) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Invalid Token',
            ];
        }

        $verified_data[$token] = null;

        if (!$is_accepted_email) {
            $date                             = new Expression('NOW()');
            $verified_data->email_verified_at = $date;
        }

        if ($verified_data->save()) {
            if($is_accepted_email){
                return [
                    'code' => $this->response_code['request_success'],
                ];
            }
            $token = UserHelper::generateToken($verified_data->id);

            return [
                'code'         => $this->response_code['request_success'],
                'access_token' => (string) $token,
            ];
        } else {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => $verified_data->errors,
            ];
        }
    }

}
