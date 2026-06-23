<?php

namespace App\Filament\Resources\WorldCupFixtures\Pages;

use App\Filament\Resources\WorldCupFixtures\WorldCupFixtureResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Http;

class ViewWorldCupFixture extends ViewRecord
{
    protected static string $resource = WorldCupFixtureResource::class;

    protected string $view = 'filament.resources.world-cup-fixtures.pages.view-world-cup-fixture';

    public function getTitle(): string
    {
        return "{$this->record->home_team_name} vs {$this->record->away_team_name}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshResult')
                ->label('Refresh Result')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Refresh match result?')
                ->modalDescription(fn(): string => "This will fetch the latest result for {$this->record->home_team_name} vs {$this->record->away_team_name} and update the database.")
                ->modalSubmitActionLabel('Yes, refresh result')
                ->action(function (): void {
                    $game = Http::retry(3, 500)
                        ->timeout(30)
                        ->connectTimeout(10)
                        ->withOptions([
                            'curl' => [
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                            ],
                        ])
                        ->get("https://worldcup26.ir/get/game/{$this->record->api_match_id}");

                    if (! $game->successful()) {
                        Notification::make()
                            ->title('Could not fetch results')
                            ->body('The World Cup API request failed.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $game = $game->json(['game']);
                    dd($game);
                    if (! $game) {
                        Notification::make()
                            ->title('Match not found')
                            ->body('No matching fixture was found in the API response.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $this->record->update([
                        'home_score' => (int) ($game['home_score'] ?? 0),
                        'away_score' => (int) ($game['away_score'] ?? 0),

                        'home_scorers' => $this->normalizeScorers($game['home_scorers'] ?? null),
                        'away_scorers' => $this->normalizeScorers($game['away_scorers'] ?? null),

                        'is_finished' => strtoupper($game['finished'] ?? 'FALSE') === 'TRUE',
                    ]);

                    $this->record->refresh();

                    Notification::make()
                        ->title('Result updated')
                        ->body("Latest result saved for {$this->record->home_team_name} vs {$this->record->away_team_name}.")
                        ->success()
                        ->send();
                }),

            Action::make('enableStream')
                ->label('Enable Live Stream')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Enable live streaming?')
                ->modalDescription(fn(): string => "This will enable live streaming for {$this->record->home_team_name} vs {$this->record->away_team_name}.")
                ->modalSubmitActionLabel('Yes, enable stream')
                ->visible(fn(): bool => ! $this->record->is_stream_enabled && ! $this->record->is_finished)
                ->action(function (): void {
                    $this->record->update([
                        'is_stream_enabled' => true,
                    ]);

                    $this->record->refresh();

                    Notification::make()
                        ->title('Live streaming enabled')
                        ->success()
                        ->send();
                }),

            Action::make('disableStream')
                ->label('Disable Live Stream')
                ->icon('heroicon-o-video-camera-slash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Disable live streaming?')
                ->modalDescription(fn(): string => "This will disable live streaming for {$this->record->home_team_name} vs {$this->record->away_team_name}.")
                ->modalSubmitActionLabel('Yes, disable stream')
                ->visible(fn(): bool => $this->record->is_stream_enabled && ! $this->record->is_finished)
                ->action(function (): void {
                    $this->record->update([
                        'is_stream_enabled' => false,
                    ]);

                    $this->record->refresh();

                    Notification::make()
                        ->title('Live streaming disabled')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function normalizeScorers(?string $value): array
    {
        if (blank($value) || strtolower($value) === 'null') {
            return [];
        }

        $value = trim($value);

        $value = str_replace(['“', '”', '„', '‟'], '"', $value);

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_values($decoded);
        }

        preg_match_all('/"([^"]+)"/', $value, $matches);

        return $matches[1] ?? [];
    }
}
