<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Category;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Address')
                                         ->schema([
                                             Forms\Components\TextInput::make('address')
                                                                       ->required()
                                                                       ->maxLength(255),
                                             Forms\Components\TextInput::make('location')
                                                                       ->required()
                                                                       ->maxLength(255),
                                         ]),
                Forms\Components\Fieldset::make('Date & Time')
                                         ->schema([
                                             Forms\Components\DatePicker::make('date')
                                                                        ->required(),
                                             Forms\Components\TextInput::make('interval')
                                                                       ->required()
                                                                       ->maxLength(255),

                                         ]),

                Forms\Components\Textarea::make('notes'),
                Forms\Components\Select::make('user_id')
                                       ->relationship('user', 'name')
                                       ->searchable()
                                       ->required(),                Forms\Components\Toggle::make('remind_me')->required(),

                Forms\Components\Repeater::make('orderCategories')
                                         ->relationship()
                                         ->collapsed()
                                         ->itemLabel(function (array $state) {
                                             return Category::find($state['category_id'])->name ?? null;
                                         })
                                         ->schema([
                                             Forms\Components\Select::make('category_id')
                                                                    ->relationship('category', 'name')
                                                                    ->searchable()
                                                                    ->required(),
                                             Forms\Components\TextInput::make('estimated_kg')
                                                                       ->numeric()
                                                                       ->minValue(1)
                                                                       ->maxValue(100),
                                         ])
                                         ->columnSpanFull(),
                Forms\Components\Fieldset::make('Confirmed orders section')
                                         ->schema([
                                             Forms\Components\Toggle::make('confirmed')
                                                                    ->required(),

                                             Forms\Components\TextInput::make('points')
                                                                       ->numeric()
                                                                       ->minValue(1),
                                         ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('address')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('location')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->searchable(),
                Tables\Columns\TextColumn::make('date')
                                         ->date()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('interval')
                                         ->searchable(),
                Tables\Columns\IconColumn::make('confirmed')
                                         ->boolean(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
