<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Категория')
                    ->relationship(name: 'category', titleAttribute: 'title')
                    ->columnSpanFull()
                    ->loadingMessage('Загрузка...'),
                TextInput::make('sku')
                    ->label('SKU')
                    ->default(null),
                TextInput::make('name')
                    ->label('Названия')
                    ->required(),
                MarkdownEditor::make('description')
                    ->label('Описание')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('Цена')
                    ->required()
                    ->numeric()
                    ->prefix('₸'),
                TextInput::make('quantity')
                    ->label('Количество')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active')
                    ->label('Опубликован')
                    ->required(),
                Textarea::make('meta')
                    ->default(null)
                    ->columnSpanFull(),

                Repeater::make('images')
                    ->columnSpanFull()
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('path')
                            ->image()
                            ->directory(fn () => 'product/' . now()->format('Y-m'))
                            ->disk('public')
                            ->imagePreviewHeight('120')
                            ->required(),
                        Toggle::make('main')->label('Main'),
                        TextInput::make('position')->numeric(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Добавить фото'),
            ]);
    }
}
