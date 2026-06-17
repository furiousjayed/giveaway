<?php

namespace App\Filament\Resources;

use App\Models\Winner;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class WinnerResource extends Resource
{
    protected static ?string $model = Winner::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Winners';

    protected static ?string $modelLabel = 'Winner';

    protected static ?string $pluralModelLabel = 'Winners';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('giveaway_id')
                    ->relationship('giveaway', 'title')
                    ->required()
                    ->searchable(),
                Select::make('participant_id')
                    ->relationship('participant', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('rank')
                    ->numeric()
                    ->required()
                    ->default(1),
                DateTimePicker::make('notified_at'),
                DateTimePicker::make('claimed_at'),
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
                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Winner Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('participant.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rank')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notified_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Notified'),
                Tables\Columns\TextColumn::make('claimed_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Claimed'),
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
            'index' => \App\Filament\Resources\WinnerResource\Pages\ListWinners::route('/'),
            'create' => \App\Filament\Resources\WinnerResource\Pages\CreateWinner::route('/create'),
            'edit' => \App\Filament\Resources\WinnerResource\Pages\EditWinner::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['giveaway', 'participant']);
    }
}
