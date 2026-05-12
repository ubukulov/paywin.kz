<?php

namespace App\Filament\Admin\Resources\Promotions\Tables;

use App\Enums\PromotionEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn (PromotionEnum $state): string => match ($state) {
                        PromotionEnum::REGISTRATION => 'Регистрация',
                        PromotionEnum::PURCHASE => 'Покупка',
                    })
                    ->color(fn (PromotionEnum $state): string => match ($state) {
                        PromotionEnum::REGISTRATION => 'info',
                        PromotionEnum::PURCHASE => 'success',
                    }),

                TextColumn::make('scope')
                    ->label('Охват')
                    ->badge()
                    // ИСПРАВЛЕНО: используем формат вместо options
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'all' => 'Все',
                        'partners' => 'Партнеры',
                        'products' => 'Товары',
                        default => $state,
                    })
                    ->color('gray'),

                TextColumn::make('reward_type')
                    ->label('Награда')
                    ->formatStateUsing(fn (string $state): string =>
                    $state === 'guaranteed' ? '🎁 Гарантированная' : '🎟 Розыгрыш'
                    ),

                ToggleColumn::make('is_active')
                    ->label('Активность'),

                TextColumn::make('gifts_count')
                    ->label('Количество')
                    ->counts('gifts') // Считает количество связанных записей в user_gifts
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('end_at')
                    ->label('Дата окончания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Активность'),

                SelectFilter::make('type')
                    ->label('Тип события')
                    ->options([
                        PromotionEnum::REGISTRATION->value => 'Регистрация',
                        PromotionEnum::PURCHASE->value => 'Покупка',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
