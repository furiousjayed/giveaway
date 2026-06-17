<?php

namespace App\Filament\Resources;

use App\Enums\GiveawayStatus;
use App\Models\Giveaway;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class GiveawayResource extends Resource
{
    protected static ?string $model = Giveaway::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Giveaways';

    protected static ?string $modelLabel = 'Giveaway';

    protected static ?string $pluralModelLabel = 'Giveaways';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->columnSpanFull(),
                TextInput::make('prize_description')
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('prize_amount')
                    ->numeric()
                    ->step(0.01)
                    ->columnSpanFull(),
                TextInput::make('max_participants')
                    ->numeric()
                    ->required()
                    ->minValue(3)
                    ->default(3),
                TextInput::make('winner_count')
                    ->numeric()
                    ->required()
                    ->default(1),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
                Select::make('status')
                    ->options([
                        'draft' => GiveawayStatus::DRAFT->label(),
                        'active' => GiveawayStatus::ACTIVE->label(),
                        'closed' => GiveawayStatus::CLOSED->label(),
                        'winners_selected' => GiveawayStatus::WINNERS_SELECTED->label(),
                    ])
                    ->default('draft')
                    ->required(),
                Toggle::make('is_public')
                    ->default(true)
                    ->label('Make Results Public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creator')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Max Slots'),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'active',
                        'warning' => 'closed',
                        'info' => 'winners_selected',
                    ])
                    ->formatStateUsing(function($state) {
                        if ($state instanceof GiveawayStatus) {
                            return $state->label();
                        }
                        return GiveawayStatus::tryFrom($state)?->label() ?? $state;
                    }),
                Tables\Columns\ToggleColumn::make('is_public')
                    ->label('Public'),
                Tables\Columns\TextColumn::make('created_at')
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
            'index' => \App\Filament\Resources\GiveawayResource\Pages\ListGiveaways::route('/'),
            'create' => \App\Filament\Resources\GiveawayResource\Pages\CreateGiveaway::route('/create'),
            'edit' => \App\Filament\Resources\GiveawayResource\Pages\EditGiveaway::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with('user')
            ->withCount('participants');
    }
}
