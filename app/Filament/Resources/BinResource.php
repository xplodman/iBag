<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BinResource\Pages;
use App\Filament\Resources\BinResource\RelationManagers\BinReadingsRelationManager;
use App\Models\Bin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BinResource extends Resource
{
    protected static ?string $model = Bin::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                                      ->schema([
                                          Forms\Components\Select::make('user_id')
                                                                 ->relationship(
                                                                     name: 'user',
                                                                     modifyQueryUsing: fn(Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
                                                                 )
                                                                 ->columnSpan('full')
                                                                 ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->first_name} {$record->last_name}")
                                                                 ->searchable([ 'first_name', 'last_name' ]),
                                          Forms\Components\TextInput::make('token')->hiddenOn('create')->columnSpan('full')->disabled(),
                                      ])
                                      ->columnSpan(['lg' => fn (?Bin $record): int => $record ? 2 : 3]),

                Forms\Components\Group::make()
                                      ->schema([
                                          Forms\Components\Section::make()
                                                                  ->schema([
                                                                      Forms\Components\Placeholder::make('created_at')
                                                                                                  ->label('Created at')
                                                                                                  ->content(fn (Bin $record): ?string => $record->created_at?->diffForHumans()),
                                                                      Forms\Components\Placeholder::make('reads_count')
                                                                                                  ->label('Total reads')
                                                                                                  ->content(fn (Bin $record) => $record->totalPayments()),
                                                                  ]),
                                      ])
                                      ->columnSpan(['lg' => 1])
                                      ->hiddenOn('create')

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->sortable(),
                Tables\Columns\TextColumn::make('token')->disabled(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BinReadingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBins::route('/'),
            'create' => Pages\CreateBin::route('/create'),
            'edit' => Pages\EditBin::route('/{record}/edit'),
        ];
    }
}
