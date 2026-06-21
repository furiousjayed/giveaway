<?php

namespace App\Filament\Resources\WorldCupFixtures\Pages;

use App\Filament\Resources\WorldCupFixtures\WorldCupFixtureResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

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
}
