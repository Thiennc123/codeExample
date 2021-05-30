<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Property;
use app\modules\api\models\User;
use Yii;
use app\modules\api\services\UserService;

class UserController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        array_push($behaviors['authenticator']['except'], 'check-email-exists');

        return $behaviors;
    }

    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);

        $users = $property->users;

        foreach ($users as $user) {
            $user->property_id = $property_id;
        }

        return [
            'code'  => $this->response_code['request_success'],
            'users' => $users,
        ];
    }

    public function actionCheckEmailExists()
    {
        $email = Yii::$app->request->get('email');

        return [
            'code'                 => $this->response_code['request_success'],
            'email_already_exists' => User::find()
                ->where(['email' => $email])->andWhere(['not', ['email_verified_at' => null]])->asArray()->exists(),
        ];
    }

    public function actionCheckEmailExistsInProperty()
    {
        $email = Yii::$app->request->get('email');
        $property_id = Yii::$app->request->get('property_id');

        $email_already_exists = User::find()->innerJoin('users_properties', '`users`.`id` = `users_properties`.`user_id`')
            ->where(['users.email' => $email, 'users_properties.property_id' => $property_id])->exists();

        return [
            'code'                 => $this->response_code['request_success'],
            'email_already_exists' => $email_already_exists,
        ];
    }
}
