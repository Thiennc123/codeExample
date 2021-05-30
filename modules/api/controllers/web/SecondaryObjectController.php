<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\SecondaryObject;
use app\modules\api\models\Property;
use app\modules\api\services\SecondaryObjectService;
use Yii;

class SecondaryObjectController extends DefaultController
{
    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $secondary_objects     = $property->secondaryObjects;

        return [
            'secondary_objects' => $secondary_objects,
            'code'    => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $secondary_object = SecondaryObjectService::createOrUpdate(Yii::$app->request->post());

        if ($secondary_object) {
            $secondary_object_res = SecondaryObject::findOne($secondary_object->id);
            return [
                'code' => $this->response_code['request_success'],
                'secondary_object' => $secondary_object_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'secondary_object' => $secondary_object->errors,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_secondary_object = SecondaryObject::findOne($id);

        if (!$edit_secondary_object) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $secondary_object = SecondaryObjectService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_secondary_object);

        if ($secondary_object) {
            $secondary_object_res = SecondaryObject::findOne($secondary_object->id);
            return [
                'code' => $this->response_code['request_success'],
                'secondary_object' => $secondary_object_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'secondary_object' => $secondary_object->errors,
        ];
    }

}
