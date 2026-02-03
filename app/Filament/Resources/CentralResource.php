<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentralResource\Pages;
use App\Filament\Resources\CentralResource\RelationManagers;
use App\Models\Central;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CentralResource extends Resource
{
    protected static ?string $model = Central::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('central_nome')
                ->required()
                ->maxLength(255),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('central_nome')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentrals::route('/'),
            'create' => Pages\CreateCentral::route('/create'),
            'edit' => Pages\EditCentral::route('/{record}/edit'),
        ];
    }
}
