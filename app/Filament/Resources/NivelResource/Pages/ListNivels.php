<?php

namespace App\Filament\Resources\NivelResource\Pages;

use App\Filament\Resources\NivelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNivels extends ListRecords
{
    protected static string $resource = NivelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
