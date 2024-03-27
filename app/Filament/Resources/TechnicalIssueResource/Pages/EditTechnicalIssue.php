<?php

namespace App\Filament\Resources\TechnicalIssueResource\Pages;

use App\Filament\Resources\TechnicalIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicalIssue extends EditRecord
{
    protected static string $resource = TechnicalIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
