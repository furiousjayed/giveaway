<?php

namespace App\Filament\Resources;

use App\Models\Participant;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Participants';

    protected static ?string $modelLabel = 'Participant';

    protected static ?string $pluralModelLabel = 'Participants';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('giveaway_id')
                    ->relationship('giveaway', 'title')
                    ->required()
                    ->searchable(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(20),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('giveaway.title')
                    ->label('Giveaway')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ParticipantResource\Pages\ListParticipants::route('/'),
            'create' => \App\Filament\Resources\ParticipantResource\Pages\CreateParticipant::route('/create'),
            'edit' => \App\Filament\Resources\ParticipantResource\Pages\EditParticipant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['giveaway', 'user']);
    }
}
