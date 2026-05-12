<?php

namespace App\Filament\Admin\Resources\Promotions\Schemas;

use App\Models\Promotion;
use App\Enums\PromotionEnum; // Импортируем ваш Enum
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                static::getTitleField(),
                static::getIsActiveField(),

                static::getTypeField(), // Теперь это Select с Enum
                static::getScopeField(),
                static::getRewardTypeField(),

                static::getStartAtField(),
                static::getEndAtField(),

                static::getPrizesField(),
            ]);
    }

    protected static function getTypeField(): Select
    {
        return Select::make('type')
            ->label('Тип акции (событие)')
            ->options([
                // Вытягиваем значения из Enum
                PromotionEnum::REGISTRATION->value => 'Регистрация',
                PromotionEnum::PURCHASE->value => 'Покупка',
            ])
            // Или если в будущем будет много вариантов, можно использовать:
            // ->options(collect(PromotionEnum::cases())->mapWithKeys(fn ($case) => [$case->value => $case->name]))
            ->required()
            ->native(false)
            ->columnSpan(1);
    }

    protected static function getTitleField(): TextInput
    {
        return TextInput::make('title')
            ->label('Название акции')
            ->required()
            ->columnSpan(2);
    }

    protected static function getIsActiveField(): Toggle
    {
        return Toggle::make('is_active')
            ->label('Активна')
            ->default(true)
            ->columnSpan(1);
    }

    protected static function getScopeField(): Select
    {
        return Select::make('scope')
            ->label('Охват')
            ->options([
                'all' => 'Все',
                'partners' => 'Партнеры',
                'products' => 'Товары',
            ])
            ->required()
            ->columnSpan(1);
    }

    protected static function getRewardTypeField(): Select
    {
        return Select::make('reward_type')
            ->label('Тип награды')
            ->options([
                'guaranteed' => 'Гарантированная',
                'raffle' => 'Розыгрыш',
            ])
            ->required()
            ->live()
            ->columnSpan(1);
    }

    protected static function getStartAtField(): DateTimePicker
    {
        return DateTimePicker::make('start_at')
            ->label('Дата начала')
            ->native(false)
            ->columnSpan(1);
    }

    protected static function getEndAtField(): DateTimePicker
    {
        return DateTimePicker::make('end_at')
            ->label('Дата окончания')
            ->native(false)
            ->columnSpan(1);
    }

    protected static function getPrizesField(): Repeater
    {
        return Repeater::make('prizes')
            ->label('Список призов (JSON)')
            ->schema([
                TextInput::make('name')->label('Наименование')->required(),
                //TextInput::make('value')->label('Кол-во / Сумма'),
            ])
            ->visible(fn ($get) => $get('reward_type') === 'guaranteed')
            ->columnSpanFull()
            ->columns(2);
    }
}
