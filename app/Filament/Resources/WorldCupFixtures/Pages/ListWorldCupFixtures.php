<?php

namespace App\Filament\Resources\WorldCupFixtures\Pages;

use App\Filament\Resources\WorldCupFixtures\WorldCupFixtureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorldCupFixtures extends ListRecords
{
    protected static string $resource = WorldCupFixtureResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
