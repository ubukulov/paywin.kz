<?php

namespace App\Filament\Admin\Resources\Shares;

use App\Filament\Admin\Resources\Shares\Pages\CreateShare;
use App\Filament\Admin\Resources\Shares\Pages\EditShare;
use App\Filament\Admin\Resources\Shares\Pages\ListShares;
use App\Filament\Admin\Resources\Shares\Schemas\ShareForm;
use App\Filament\Admin\Resources\Shares\Tables\SharesTable;
use App\Models\Share;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShareResource extends Resource
{
    protected static ?string $model = Share::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | \UnitEnum | null $navigationGroup = 'Партнеры';
    protected static ?string $navigationLabel = 'Акции';
    protected static ?string $modelLabel = 'Партнер';
    protected static ?string $pluralModelLabel = 'Список акции';

    protected static ?string $recordTitleAttribute = 'Акции';

    public static function form(Schema $schema): Schema
    {
        return ShareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SharesTable::configure($table);
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
            'index' => ListShares::route('/'),
            'create' => CreateShare::route('/create'),
            'edit' => EditShare::route('/{record}/edit'),
        ];
    }
}
