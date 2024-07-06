<?php
namespace App\Filament\Resources\SliderResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\SliderResource;
use Illuminate\Routing\Router;


class SliderApiService extends ApiService
{
    protected static string | null $resource = SliderResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
