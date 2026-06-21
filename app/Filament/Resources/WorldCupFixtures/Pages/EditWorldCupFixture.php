<?php

namespace App\Filament\Resources\WorldCupFixtures\Pages;

use App\Filament\Resources\WorldCupFixtures\WorldCupFixtureResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorldCupFixture extends EditRecord
{
    protected static string $resource = WorldCupFixtureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
