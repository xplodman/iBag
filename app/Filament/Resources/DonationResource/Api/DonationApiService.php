<?php

namespace App\Filament\Resources\DonationResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\DonationResource;
use Illuminate\Routing\Router;

class DonationApiService extends ApiService
{
    protected static string | null $resource = DonationResource::class;

    public static function handlers(): array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];
    }
}
