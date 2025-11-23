<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->getStateUsing(fn ($record) => $record->user?->name ?? 'не указан')
                    ->label('Заказчик'),

                TextColumn::make('discount')
                    ->label('Скидка'),

                TextColumn::make('shipping_cost')
                    ->money('KZT')
                    ->label('Стоимость доставки'),

                TextColumn::make('total')
                    ->money('KZT')
                    ->label('Сумма'),

                TextColumn::make('shipping_address')
                    ->label('Адрес доставки')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
