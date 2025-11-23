<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Товары заказа';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Товар'),

                TextColumn::make('quantity')
                    ->label('Количество'),

                TextColumn::make('price')
                    ->money('KZT')
                    ->label('Цена'),

                TextColumn::make('total')
                    ->money('KZT')
                    ->label('Итого'),
            ])
            ->headerActions([])   // убрать кнопки создания
            ->recordActions([]);  // убрать кнопки редактирования
    }
}
