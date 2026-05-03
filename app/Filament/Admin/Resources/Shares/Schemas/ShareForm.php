<?php

namespace App\Filament\Admin\Resources\Shares\Schemas;

use App\Models\Share;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Support\Str;

class ShareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                ->label('Названия')
                ->required(),
                
            ]);
    }
}
