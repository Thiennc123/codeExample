<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\helpers\PermissionHelper;
use app\modules\api\models\Property;
use app\modules\api\models\User;
use app\modules\api\models\UserProperty;
use app\modules\api\notifications\AcceptInviteEmail;
use app\modules\api\services\PropertyService;
use Yii;

class PropertyController extends DefaultController
{
    public $modelClass = 'app\modules\api\models\Property';

    public function actionIndex()
    {
        $properties = PropertyService::getPropertiesForCurrentUser();

        return [
            'properties' => $properties,
            'code'       => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $property = PropertyService::createOrUpdate(Yii::$app->request->post());

        if ($property) {
            return [
                'code'     => $this->response_code['request_success'],
                'property' => $property,
            ];
        }
        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => $property->errors,
        ];
    }

    public function actionView($id)
    {
        $property        = Property::findOne($id);
        $primary_objects = $property->primaryObjects;

        return [
            'code'            => $this->response_code['request_success'],
            'property'        => $property,
            'primary_objects' => $primary_objects,
        ];
    }

    public function actionUpdate($id)
    {
        $editted_property = Property::findOne($id);
        if (!$editted_property) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }
        $property = PropertyService::createOrUpdate(Yii::$app->request->getBodyParams(), $editted_property);
        if ($property) {
            return [
                'code'     => $this->response_code['request_success'],
                'property' => $property,
            ];
        }
        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => $property->errors,
        ];
    }

    public function actionInvite($id)
    {
        $property      = Property::findOne($id);
        $user          = User::find()->where(['email' => Yii::$app->request->post('user_email')])->one();
        $permission_id = PermissionHelper::convertSlugToPermissionId(Yii::$app->request->post('permission'));
        $isCreatedUser = false;

        if (!$user) {
            $user            = new User();
            $user_attributes = [
                'name'     => Yii::$app->security->generateRandomString(5),
                'password' => Yii::$app->security->generateRandomString(7),
                'email'    => Yii::$app->request->post('user_email'),
            ];

            $user->attributes = $user_attributes;
            $user->save();
            $isCreatedUser = true;
        }

        $user_property = PropertyService::createUserProperties($property, $permission_id, $user->id);

        Yii::$app->queue->delay(5)->push(new AcceptInviteEmail([
            'base_url'      => $this->constants['base_url'],
            'user'          => $user,
            'property'      => $property,
            'invited_token' => $user_property->invited_token,
            'isCreatedUser' => $isCreatedUser,
        ]));

        return [
            'code'          => $this->response_code['request_success'],
            'user_property' => $user_property,
        ];
    }

    public function actionCancelInvite($id)
    {
        $user_id       = Yii::$app->request->get('user_id');
        $user_property = UserProperty::find()->where(['user_id' => $user_id, 'property_id' => $id])->one();
        $user_property->delete();

        return [
            'code' => $this->response_code['request_success'],
        ];
    }
}
