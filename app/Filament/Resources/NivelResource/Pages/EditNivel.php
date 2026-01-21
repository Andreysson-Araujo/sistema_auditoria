<?php

namespace App\Filament\Resources\NivelResource\Pages;

use App\Filament\Resources\NivelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNivel extends EditRecord
{
    protected static string $resource = NivelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
