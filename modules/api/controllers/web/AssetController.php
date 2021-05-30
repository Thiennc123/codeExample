<?php

namespace app\modules\api\controllers\web;

use app\modules\api\controllers\DefaultController;
use app\modules\api\models\Asset;
use app\modules\api\models\Property;
use app\modules\api\services\AssetService;
use Yii;

class AssetController extends DefaultController
{
    public function actionIndex()
    {
        $property_id = Yii::$app->request->get('property_id');
        $property    = Property::findOne($property_id);
        $assets     = $property->assets;

        return [
            'assets' => $assets,
            'code'    => $this->response_code['request_success'],
        ];
    }

    public function actionCreate()
    {
        $asset = AssetService::createOrUpdate(Yii::$app->request->post());
        if ($asset) {
            $asset_res = Asset::findOne($asset->id);
            return [
                'code' => $this->response_code['request_success'],
                'asset' => $asset_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'asset' => $asset->errors,
        ];
    }

    public function actionUpdate($id)
    {
        $edit_asset = Asset::findOne($id);

        if (!$edit_asset) {
            return [
                'code'    => $this->response_code['request_invalid'],
                'message' => 'Items are not found',
            ];
        }

        $asset = AssetService::createOrUpdate(Yii::$app->request->getBodyParams(), $edit_asset);

        if ($asset) {
            $asset_res = Asset::findOne($asset->id);
            return [
                'code' => $this->response_code['request_success'],
                'asset' => $asset_res,
            ];
        }
        return [
            'code' => $this->response_code['request_invalid'],
            'asset' => $asset->errors,
        ];
    }

    public function actionDelete($id)
    {
        $asset = Asset::findOne($id);
        if ($asset) {
            $asset->delete();
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
