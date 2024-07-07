<?php

namespace App\Filament\Resources\BinResource\Pages;

use App\Filament\Resources\BinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditBin extends EditRecord
{
    #[On('refreshBin')]
    public function refresh(): void
    {
    }

    protected static string $resource = BinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
