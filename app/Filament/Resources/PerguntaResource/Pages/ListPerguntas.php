<?php

namespace App\Filament\Resources\PerguntaResource\Pages;

use App\Filament\Resources\PerguntaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerguntas extends ListRecords
{
    protected static string $resource = PerguntaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
