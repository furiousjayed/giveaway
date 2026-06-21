<?php

namespace App\Filament\Resources\WorldCupFixtures\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WorldCupFixtureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('api_match_id'),
                TextEntry::make('group_code')
                    ->placeholder('-'),
                TextEntry::make('matchday')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('match_type')
                    ->placeholder('-'),
                TextEntry::make('home_team_name')
                    ->placeholder('-'),
                TextEntry::make('away_team_name')
                    ->placeholder('-'),
                TextEntry::make('home_scorers')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('away_scorers')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('home_score')
                    ->numeric(),
                TextEntry::make('away_score')
                    ->numeric(),
                TextEntry::make('stadium_name'),
                TextEntry::make('city'),
                TextEntry::make('host_country'),
                TextEntry::make('timezone'),
                TextEntry::make('start_at')
                    ->dateTime(),
                IconEntry::make('is_finished')
                    ->boolean(),
                IconEntry::make('is_stream_enabled')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
