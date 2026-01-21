<?php

namespace App\Filament\Resources\CentralResource\Pages;

use App\Filament\Resources\CentralResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentrals extends ListRecords
{
    protected static string $resource = CentralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
