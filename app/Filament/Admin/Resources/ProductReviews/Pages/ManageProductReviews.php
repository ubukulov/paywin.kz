<?php

namespace App\Filament\Admin\Resources\ProductReviews\Pages;

use App\Filament\Admin\Resources\ProductReviews\ProductReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProductReviews extends ManageRecords
{
    protected static string $resource = ProductReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
