<?php

namespace App\Filament\Resources\OrderResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\OrderResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = OrderResource::class;


    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        );

        if (! auth()->user()->is_provider) {
            $query = $query->where('user_id', auth()->user()->id);
        }

        $query = $query->first();

        if (!$query) {
            return static::sendNotFoundResponse();
        }

        $transformer = static::getApiTransformer();

        return new $transformer($query);
    }
}
