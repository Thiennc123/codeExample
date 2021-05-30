<?php

namespace app\modules\api\services;

use app\modules\api\helpers\CommonHelper;
use app\modules\api\models\Breed;
use Yii;

class BreedService
{
    public static function createOrUpdate($request, $edit_breed = null)
    {
        $breed_attributes   = $request['breed'];
       
        if ($edit_breed) {
            $breed = $edit_breed;
        } else {
            $breed = new Breed();
        }

        $breed_attributes['slug'] = str_replace(' ', '_', strtolower($breed_attributes['name']));

        $breed->attributes = $breed_attributes;

        $edit_breed ? $breed->update() : $breed->save();

        return $breed;
    }
}
