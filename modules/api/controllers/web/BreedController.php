<?php

namespace app\modules\api\controllers\web;
use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Breed;
use app\modules\api\models\Property;
use app\modules\api\services\BreedService;
use Yii;

class BreedController extends DefaultController
{
    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $breeds      = $property->breeds;

        return [
            'breeds' => $breeds,
            'code'    => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $breed = BreedService::createOrUpdate(Yii::$app->request->post());

        if ($breed) {
            $breed_res = Breed::findOne($breed->id);
            return [
                'code' => $this->response_code['request_success'],
                'breed' => $breed_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'breed' => $breed->errors,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_breed = Breed::findOne($id);

        if (!$edit_breed) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $breed = BreedService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_breed);

        if ($breed) {
            $breed_res = Breed::findOne($breed->id);
            return [
                'code' => $this->response_code['request_success'],
                'breed' => $breed_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'breed' => $breed->errors,
        ];
    }

}
