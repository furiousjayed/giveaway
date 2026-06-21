<?php

namespace App\Filament\Resources\WorldCupFixtures\Tables;

use App\Models\WorldCupFixture;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class WorldCupFixturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('home_team_name')
                    ->label('Home Team')
                    ->searchable(),
                TextColumn::make('away_team_name')
                    ->label('Away Team')
                    ->searchable(),

                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('group_code')
                    ->label('Group')
                    ->searchable(),
                TextColumn::make('matchday')
                    ->label('Match No')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('match_type')
                    ->searchable(),

                TextColumn::make('host_country')
                    ->searchable(),

                IconColumn::make('is_finished')
                    ->boolean(),
                IconColumn::make('is_stream_enabled')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_stream_enabled')
                    ->label('Steam Enabled'),
                TernaryFilter::make('is_finished'),
                SelectFilter::make('group_code')
                    ->label('Group')
                    ->options(
                        fn(): array => WorldCupFixture::query()
                            ->whereNotNull('group_code')
                            ->distinct()
                            ->orderBy('group_code')
                            ->pluck('group_code', 'group_code')
                            ->toArray()
                    ),
                Filter::make('start_at')
                    ->label('Kickoff Date')
                    ->schema([
                        DatePicker::make('start_from')
                            ->label('From')
                            ->native(false),

                        DatePicker::make('start_until')
                            ->label('Until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['start_from'] ?? null;
                        $until = $data['start_until'] ?? null;

                        if (! $from && ! $until) {
                            return $query;
                        }

                        /*
         * start_at is stored in UTC.
         * This filter treats selected dates as the fixture's local stadium date,
         * using each row's timezone column.
         */
                        $timezones = WorldCupFixture::query()
                            ->whereNotNull('timezone')
                            ->distinct()
                            ->pluck('timezone')
                            ->filter()
                            ->values();

                        return $query->where(function (Builder $query) use ($timezones, $from, $until): void {
                            foreach ($timezones as $timezone) {
                                $fromUtc = $from
                                    ? Carbon::parse($from, $timezone)->startOfDay()->utc()
                                    : null;

                                $untilUtc = $until
                                    ? Carbon::parse($until, $timezone)->endOfDay()->utc()
                                    : null;

                                $query->orWhere(function (Builder $query) use ($timezone, $fromUtc, $untilUtc): void {
                                    $query
                                        ->where('timezone', $timezone)
                                        ->when(
                                            $fromUtc,
                                            fn(Builder $query): Builder => $query->where('start_at', '>=', $fromUtc)
                                        )
                                        ->when(
                                            $untilUtc,
                                            fn(Builder $query): Builder => $query->where('start_at', '<=', $untilUtc)
                                        );
                                });
                            }
                        });
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_from'] ?? null) {
                            $indicators[] = Indicator::make('Kickoff from ' . Carbon::parse($data['start_from'])->toFormattedDateString())
                                ->removeField('start_from');
                        }

                        if ($data['start_until'] ?? null) {
                            $indicators[] = Indicator::make('Kickoff until ' . Carbon::parse($data['start_until'])->toFormattedDateString())
                                ->removeField('start_until');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('enableStream')
                    ->label('Enable Stream')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enable live streaming?')
                    ->modalDescription(fn(WorldCupFixture $record): string => "This will enable live streaming for {$record->home_team_name} vs {$record->away_team_name}.")
                    ->modalSubmitActionLabel('Yes, enable stream')
                    ->visible(fn(WorldCupFixture $record): bool => ! $record->is_stream_enabled && ! $record->is_finished)
                    ->action(function (WorldCupFixture $record): void {
                        $record->update([
                            'is_stream_enabled' => true,
                        ]);

                        Notification::make()
                            ->title('Live streaming enabled')
                            ->success()
                            ->send();
                    }),

                Action::make('disableStream')
                    ->label('Disable Stream')
                    ->icon('heroicon-o-video-camera-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Disable live streaming?')
                    ->modalDescription(fn(WorldCupFixture $record): string => "This will disable live streaming for {$record->home_team_name} vs {$record->away_team_name}.")
                    ->modalSubmitActionLabel('Yes, disable stream')
                    ->visible(fn(WorldCupFixture $record): bool => $record->is_stream_enabled && ! $record->is_finished)
                    ->action(function (WorldCupFixture $record): void {
                        $record->update([
                            'is_stream_enabled' => false,
                        ]);

                        Notification::make()
                            ->title('Live streaming disabled')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
