<?php

namespace App\Filament\Resources\RespostaResource\Pages;

use App\Filament\Resources\RespostaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRespostas extends ListRecords
{
    protected static string $resource = RespostaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
