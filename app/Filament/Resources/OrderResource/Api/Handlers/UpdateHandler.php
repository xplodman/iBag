<?php

namespace App\Filament\Resources\OrderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\OrderResource;

class UpdateHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = OrderResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::where('id', $id);

        if (! auth()->user()->is_provider) {
            $model = $model->where('user_id', auth()->user()->id);
        }

        $model = $model->first();

        if (!$model) {
            return static::sendNotFoundResponse();
        }

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}
