<?php

namespace App\Filament\Resources\DonationResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\DonationResource;

class DeleteHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = DonationResource::class;

    public static function getMethod()
    {
        return Handlers::DELETE;
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

        $model->delete();

        return static::sendSuccessResponse($model, "Successfully Delete Resource");
    }
}
