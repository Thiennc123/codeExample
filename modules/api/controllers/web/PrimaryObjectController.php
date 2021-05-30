<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\PrimaryObject;
use app\modules\api\models\Property;
use app\modules\api\models\Mob;
use app\modules\api\models\Task;
use app\modules\api\services\PrimaryObjectService;
use app\modules\api\services\MobService;
use Yii;

class PrimaryObjectController extends DefaultController
{

    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $objects     = $property->getPrimaryObjects()->with('assets')->all();
        
        return [
            'objects' => $objects,
            'code'    => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $primary_object = PrimaryObjectService::createOrUpdate(Yii::$app->request->post());

        if ($primary_object) {
            return [
                'primary_object' => $primary_object,
                'code'           => $this->response_code['request_success'],
            ];
        } else {
            return [
                'message' => $primary_object->errors,
                'code'    => $this->response_code['request_invalid'],
            ];
        }
    }

    public function actionUpdate($id)
    {
        $edit_object = PrimaryObject::findOne($id);

        if (!$edit_object) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $primary_object = PrimaryObjectService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_object);

        if ($primary_object) {
            return [
                'code'           => $this->response_code['request_success'],
                'primary_object' => $primary_object,
            ];
        }
        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => $primary_object->errors,
        ];
    }

    public function actionUpdateArea($id)
    {
        $primary_object = PrimaryObject::findOne($id);

        if (!$primary_object) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $primary_object_attributes = Yii::$app->request->getBodyParams()['primary_object'];
        $data_area                 = $primary_object_attributes['data_area'];
        $data_area[]               = $primary_object_attributes['data_area'][0];
        $primary_object->data_area = $data_area;
        $primary_object->attributes = $primary_object_attributes;

        if ($primary_object->save()) {
            $edited_primary = PrimaryObject::findOne($id);
            $mobs_in_primary = Mob::findAll(['primary_object_id' => $id]);
            foreach ($mobs_in_primary as $mob) {
                $mob->point = [
                    'lat' => $edited_primary->lat_centroid,
                    'lng' => $edited_primary->lng_centroid,
                ];
                $mob->update();
            }
            
            return [
                'code' => $this->response_code['request_success'],
            ];
        }
        return [
            'code'    => $this->response_code['request_invalid'],
            'message' => $primary_object->errors,
        ];
    }

    public function actionDelete($id)
    {
        $primary_object = PrimaryObject::findOne($id);
        
        if ($primary_object) {
            $mobs_in_primary = Mob::deleteAll(['primary_object_id' => $id]);
            $tasks_in_primary = Task::updateAll(['primary_object_id' => 0], ['primary_object_id' => $id]);

            $primary_object->delete();

            return [
                'code' => $this->response_code['request_success'],
            ];
        }

        return [
            'code'    => $this->response_code['request_failed'],
            'message' => 'Item is not found',
        ];
    }
}
