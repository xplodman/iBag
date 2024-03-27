<?php

namespace App\Filament\Resources\DonationResource\Api\Handlers;

use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\DonationResource;

class CreateHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = DonationResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());
        $model->user_id = auth()->user()->id;
        $model->save();

        foreach ($request->categories as $category) {
                $donationCategory = new DonationCategory();
                $donationCategory->fill($category);
                $model->donationCategories()->save($donationCategory);
        }

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
