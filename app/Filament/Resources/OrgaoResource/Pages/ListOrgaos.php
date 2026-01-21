<?php

namespace App\Filament\Resources\OrgaoResource\Pages;

use App\Filament\Resources\OrgaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrgaos extends ListRecords
{
    protected static string $resource = OrgaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
