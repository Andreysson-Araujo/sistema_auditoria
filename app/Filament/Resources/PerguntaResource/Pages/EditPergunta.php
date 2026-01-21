<?php

namespace App\Filament\Resources\PerguntaResource\Pages;

use App\Filament\Resources\PerguntaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPergunta extends EditRecord
{
    protected static string $resource = PerguntaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
