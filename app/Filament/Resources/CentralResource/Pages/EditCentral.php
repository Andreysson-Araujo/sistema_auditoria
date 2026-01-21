<?php

namespace App\Filament\Resources\CentralResource\Pages;

use App\Filament\Resources\CentralResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentral extends EditRecord
{
    protected static string $resource = CentralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
