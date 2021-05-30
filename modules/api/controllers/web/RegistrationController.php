<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\User;
use app\modules\api\models\UserProperty;
use app\modules\api\notifications\VerifyApiEmail;
use app\modules\api\services\UserService;
use Yii;
use yii\db\Expression;

class RegistrationController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\SignupForm';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        array_push($behaviors['authenticator']['except'], 'create', 'resend-email-confirmation');

        return $behaviors;
    }

    public function actionCreate()
    {
        $user_attributes = Yii::$app->request->post('user');
        $invited_token   = Yii::$app->request->post('token');
        $checkSentMail   = false;

        if ($invited_token) {
            $user = UserService::getUserByInvitedToken($invited_token);
        } else {
            $user = User::find()->where(['email' => $user_attributes['email']])->one();
        }

        if (!isset($user)) {
            $checkSentMail                         = true;
            $user_attributes['confirmation_token'] = Yii::$app->security->generateRandomString(32);

            $user = new User();
        } else {
            if (!$invited_token) {
                $checkSentMail                         = true;
                $user_attributes['confirmation_token'] = Yii::$app->security->generateRandomString(32);
            } else {
                $date                                 = new Expression('NOW()');
                $user_attributes['email_verified_at'] = $date;
            }
        }

        $user->attributes = $user_attributes;

        if ($user->save()) {
            if ($checkSentMail) {
                Yii::$app->queue->delay(5)->push(new VerifyApiEmail([
                    'base_url' => $this->constants['base_url'],
                    'user'     => $user,
                ]));
            } else {
                $user_property                = UserProperty::find()->where(['invited_token' => $invited_token])->one();
                $user_property->invited_token = null;
                $user_property->save();
            }

            return [
                'code'    => $this->response_code['request_success'],
                'message' => 'Success',
            ];
        }

        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => $user->errors,
        ];
    }

    public function actionResendEmailConfirmation()
    {
        $user_email = Yii::$app->request->get('email');
        $user       = User::find()->where(['email' => $user_email])->one();

        if ($user) {
            if (!$user->confirmation_token && $user->email_verified_at) {
                return [
                    'code'    => $this->response_code['request_invalid'],
                    'message' => "Your email is confirmed",
                ];
            } else {
                Yii::$app->queue->delay(5)->push(new VerifyApiEmail([
                    'base_url' => $this->constants['base_url'],
                    'user'     => $user,
                ]));
                return [
                    'code' => $this->response_code['request_success'],
                ];
            }
        }

        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => "Your email didn't exist in our system",
        ];
    }
}
