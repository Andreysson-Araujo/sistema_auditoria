<?php

namespace App\Filament\Resources\PilarResource\Pages;

use App\Filament\Resources\PilarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilars extends ListRecords
{
    protected static string $resource = PilarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
