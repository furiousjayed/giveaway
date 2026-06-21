<?php

namespace App\Filament\Resources\WorldCupFixtures\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorldCupFixtureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_match_id')
                    ->required(),
                TextInput::make('group_code'),
                TextInput::make('matchday')
                    ->numeric(),
                TextInput::make('match_type'),
                TextInput::make('home_team_name'),
                TextInput::make('away_team_name'),
                Textarea::make('home_scorers')
                    ->columnSpanFull(),
                Textarea::make('away_scorers')
                    ->columnSpanFull(),
                TextInput::make('home_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('away_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stadium_name')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('host_country')
                    ->required(),
                TextInput::make('timezone')
                    ->required(),
                DateTimePicker::make('start_at')
                    ->required(),
                Toggle::make('is_finished')
                    ->required(),
                Toggle::make('is_stream_enabled')
                    ->required(),
            ]);
    }
}
