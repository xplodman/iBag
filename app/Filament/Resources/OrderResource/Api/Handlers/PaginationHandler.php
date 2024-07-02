<?php
namespace App\Filament\Resources\OrderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\OrderResource;

class PaginationHandler extends Handlers
{
    public static string | null $uri = '/';
    public static string | null $resource = OrderResource::class;

    public function handler()
    {
        $query = static::getEloquentQuery()->with(['user', 'orderCategories', 'orderCategories.category']);
        $model = static::getModel();

        $query = QueryBuilder::for($query)
        ->allowedFields($model::$allowedFields ?? [])
        ->allowedSorts($model::$allowedSorts ?? [])
        ->allowedFilters($model::$allowedFilters ?? [])
        ->allowedIncludes($model::$allowedIncludes ?? null);

        if (! auth()->user()->is_provider) {
            $query = $query->where('user_id', auth()->user()->id);
        }

        $query = $query->paginate(request()->query('per_page'))
                       ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
