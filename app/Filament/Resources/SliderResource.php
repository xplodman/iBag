<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                         ->required()
                         ->columnSpanFull()
                         ->maxLength(255),
                RichEditor::make('description')
                          ->toolbarButtons([
                              'bold',
                              'bulletList',
                              'h2',
                              'h3',
                              'italic',
                              'orderedList',
                              'redo',
                              'strike',
                              'underline',
                              'undo',
                          ])->columnSpanFull(),
                TextInput::make('button_text')
                         ->columnSpanFull()
                         ->maxLength(255),
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachment')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                                         ->searchable()
                                         ->description(function (Slider $record) {
                                             return new HtmlString($record->description);
                                         })
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('button_text')
                                         ->searchable()
                                         ->description(function (Slider $record) {
                                             return new HtmlString($record->description);
                                         })
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                                         ->toggleable()
                                         ->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('attachment')
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('deleted_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
