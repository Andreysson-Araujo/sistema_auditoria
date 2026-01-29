<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerguntaResource\Pages;
use App\Filament\Resources\PerguntaResource\RelationManagers;
use App\Models\Pergunta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerguntaResource extends Resource
{
    protected static ?string $model = Pergunta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pilar_id')
                    ->relationship('pilar', 'pilar_value') // Busca na model Pilar o campo pilar_value
                    ->required()
                    ->label('Selecione o Pilar'),

                Forms\Components\Textarea::make('texto_pergunta')
                    ->required()
                    ->label('Texto da Pergunta')
                    ->columnSpanFull(),

                Forms\Components\Select::make('tipo')
                    ->options([
                        'nota' => 'Escala de 1 a 5',
                        'sim_nao' => 'Sim ou Não',
                        'texto' => 'Resposta Discursiva',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //return $table
                // Exibe o Valor do Pilar (já que você não tem o nome)
                Tables\Columns\TextColumn::make('pilar.pilar_value')
                    ->label('Valor do Pilar')
                    ->sortable(),

                // Exibe o texto da pergunta
                Tables\Columns\TextColumn::make('texto_pergunta')
                    ->label('Pergunta')
                    ->limit(50) // Corta o texto se for muito longo
                    ->searchable(),

                // Exibe o tipo da pergunta com um Badge (etiqueta colorida)
                Tables\Columns\TextColumn::make('tipo')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'nota' => 'success',
                        'sim_nao' => 'warning',
                        'texto' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPerguntas::route('/'),
            'create' => Pages\CreatePergunta::route('/create'),
            'edit' => Pages\EditPergunta::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
}
}
