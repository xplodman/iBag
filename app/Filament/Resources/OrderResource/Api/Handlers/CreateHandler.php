<?php

namespace App\Filament\Resources\OrderResource\Api\Handlers;

use App\Models\OrderCategory;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\OrderResource;

class CreateHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = OrderResource::class;

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
                $orderCategory = new OrderCategory();
                $orderCategory->fill($category);
                $model->orderCategories()->save($orderCategory);
        }

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
