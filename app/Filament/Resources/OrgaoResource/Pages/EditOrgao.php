<?php

namespace App\Filament\Resources\OrgaoResource\Pages;

use App\Filament\Resources\OrgaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrgao extends EditRecord
{
    protected static string $resource = OrgaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
