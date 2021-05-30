<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Mob;
use app\modules\api\models\Property;
use app\modules\api\services\MobService;
use Yii;

class MobController extends DefaultController
{
    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $mobs     	 = $property->mobs;

        return [
            'mobs' => $mobs,
            'code'    => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $mob = MobService::createOrUpdate(Yii::$app->request->post());

        if ($mob) {
            $mob_res = Mob::findOne($mob->id);
            return [
                'code' => $this->response_code['request_success'],
                'mob' => $mob_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'mob' => $mob->errors,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_mob = Mob::findOne($id);

        if (!$edit_mob) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $mob = MobService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_mob);

        if ($mob) {
            $mob_res = Mob::findOne($mob->id);
            return [
                'code' => $this->response_code['request_success'],
                'mob' => $mob_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'mob' => $mob->errors,
        ];
    }

    public function actionDelete($id)
    {
        $mob = Mob::findOne($id);
        if ($mob) {
            $mob->delete();
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
