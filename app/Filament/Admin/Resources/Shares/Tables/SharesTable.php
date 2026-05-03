<?php

namespace App\Filament\Admin\Resources\Shares\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SharesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Выводим имя партнера через связь 'partner', которую мы описали в модели
                TextColumn::make('partner.partnerProfile.company')
                    ->label('Партнер')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->wrap(), // Позволяет тексту переноситься на новую строку

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'promocode' => 'info',
                        'share' => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gift' => 'Приз',
                        'discount' => 'Скидки',
                        'cashback' => 'Кешбек',
                        'promocode' => 'Промокод',
                        'share' => 'Акция',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('used_count')
                    ->label('Использовано / Лимит')
                    ->state(function ($record) {
                        $limit = $record->count > 0 ? $record->count : '∞';
                        return "{$record->used_count} / {$limit}";
                    })
                    ->sortable(['used_count']), // Сортируем по реальному полю в БД

                // Используем оптимизированный метод isActive() из модели Share
                IconColumn::make('is_active')
                    ->label('Статус')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->isActive()),

                TextColumn::make('from_date')
                    ->label('Начало')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('to_date')
                    ->label('Окончание')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Фильтр по типу (Акция или Промокод)
                SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'promocode' => 'Промокод',
                        'share' => 'Акция',
                    ]),

                // Фильтр по партнеру (позволяет быстро найти акции конкретного магазина)
                SelectFilter::make('partner_id')
                    ->label('Партнер')
                    ->relationship('partner.partnerProfile', 'company')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
