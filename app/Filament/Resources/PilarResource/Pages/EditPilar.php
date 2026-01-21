<?php

namespace App\Filament\Resources\PilarResource\Pages;

use App\Filament\Resources\PilarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPilar extends EditRecord
{
    protected static string $resource = PilarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
