<?php

namespace App\Filament\Admin\Resources\ProductReviews;

use App\Filament\Admin\Resources\ProductReviews\Pages\ManageProductReviews;
use App\Models\ProductReview;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Schema;

// РАЗДЕЛЯЕМ ИМПОРТЫ ДЛЯ FILAMENT v4:
use Filament\Schemas\Components\Section; // Структурный лейаут живет в Schemas
use Filament\Forms\Components\Select;    // Поля ввода всё еще живут в Forms
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

// Экшены v4:
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Отзывы';

    protected static ?string $pluralModelLabel = 'Отзывы покупателей';

    protected static ?string $modelLabel = 'Отзыв';

    protected static string|\UnitEnum|null $navigationGroup = 'Управление контентом';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Товар')
                    ->disabled(),

                Select::make('user_id')
                    ->relationship('user', 'email')
                    ->label('Покупатель')
                    ->disabled(),

                TextInput::make('rating')
                    ->label('Оценка')
                    ->numeric()
                    ->disabled(),

                Textarea::make('comment')
                    ->label('Текст отзыва')
                    ->columnSpanFull()
                    ->disabled(),

                Section::make('Ответ администрации')
                    ->schema([
                        Textarea::make('admin_reply')
                            ->label('Ваш ответ')
                            ->rows(3),
                        DateTimePicker::make('replied_at')
                            ->label('Дата ответа')
                            ->disabled(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Покупатель')
                    ->searchable()
                    ->default(fn ($record) => $record->user?->email),

                TextColumn::make('rating')
                    ->label('Оценка')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state === 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('comment')
                    ->label('Отзыв')
                    ->limit(40)
                    ->searchable(),

                ToggleColumn::make('is_approved')
                    ->label('Одобрен'),

                TextColumn::make('admin_reply')
                    ->label('Статус ответа')
                    ->state(fn (ProductReview $record): string => $record->admin_reply ? '✅ Отвечен' : '⏳ Ждет ответа')
                    ->badge()
                    ->color(fn (string $state): string => $state === '✅ Отвечен' ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Модерация')
                    ->placeholder('Все отзывы')
                    ->trueLabel('Только одобренные')
                    ->falseLabel('Скрытые'),

                TernaryFilter::make('has_reply')
                    ->label('Наличие ответа')
                    ->placeholder('Все')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('admin_reply'),
                        false: fn ($query) => $query->whereNull('admin_reply'),
                    ),

                SelectFilter::make('rating')
                    ->label('Оценка')
                    ->options([
                        5 => '5 звезд',
                        4 => '4 звезды',
                        3 => '3 звезды',
                        2 => '2 звезды',
                        1 => '1 звезда',
                    ]),
            ])
            ->actions([
                Action::make('reply')
                    ->label('Ответить')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('warning')
                    ->modalHeading(fn (ProductReview $record) => "Ответ на отзыв от " . ($record->user->name ?? $record->user->email))
                    ->modalDescription(fn (ProductReview $record) => "Отзыв: \"{$record->comment}\"")
                    ->form([
                        // Здесь также напрямую вызывается корректный импортированный Textarea
                        Textarea::make('admin_reply')
                            ->label('Текст ответа администрации')
                            ->required()
                            ->rows(4)
                            ->placeholder('Напишите официальный ответ платформы Paywin...'),
                    ])
                    ->mountUsing(fn ($form, ProductReview $record) => $form->fill([
                        'admin_reply' => $record->admin_reply,
                    ]))
                    ->action(function (ProductReview $record, array $data): void {
                        $record->update([
                            'admin_reply' => $data['admin_reply'],
                            'replied_at' => now(),
                        ]);
                    })
                    ->successNotificationTitle('Ответ успешно сохранен'),

                EditAction::make()->label('Просмотр'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            // Теперь вызываем класс напрямую без префиксов
            'index' => ManageProductReviews::route('/'),
        ];
    }
}
