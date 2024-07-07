<?php

namespace App\Filament\Resources\BinResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class BinReadingsRelationManager extends RelationManager
{
    protected static string $relationship = 'binReadings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sensor_readings.Metal_Bin_Level')
                         ->label('Metal Bin Level')
                         ->required()
                         ->numeric(),
                Forms\Components\TextInput::make('sensor_readings.Organic_Bin_Level')
                         ->label('Organic Bin Level')
                         ->required()
                         ->numeric(),
                Forms\Components\TextInput::make('sensor_readings.In_Organic_Bin_Level')
                         ->label('Inorganic Bin Level')
                         ->required()
                         ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sensor_readings')
            ->columns([
                Tables\Columns\TextColumn::make('sensor_readings.Metal_Bin_Level')
                                                         ->label('Metal Bin Level')->sortable(false),
                Tables\Columns\TextColumn::make('sensor_readings.Organic_Bin_Level')
                          ->label('Organic Bin Level')->sortable(false),
                Tables\Columns\TextColumn::make('sensor_readings.In_Organic_Bin_Level')
                          ->label('Inorganic Bin Level')->sortable(false),
                Tables\Columns\TextColumn::make('created_at')
                          ->dateTime()
                          ->sortable(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                                           ->after(function (Component $livewire) {
                                               $livewire->dispatch('refreshBins');
                                           }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshBins');
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (Component $livewire) {
                        $livewire->dispatch('refreshBins');
                    }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                                           ->after(function (Component $livewire) {
                                               $livewire->dispatch('refreshBins');
                                           }),
            ]);
    }
}
