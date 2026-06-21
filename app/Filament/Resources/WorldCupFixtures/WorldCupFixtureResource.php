<?php

namespace App\Filament\Resources\WorldCupFixtures;

use App\Filament\Resources\WorldCupFixtures\Pages\CreateWorldCupFixture;
use App\Filament\Resources\WorldCupFixtures\Pages\EditWorldCupFixture;
use App\Filament\Resources\WorldCupFixtures\Pages\ListWorldCupFixtures;
use App\Filament\Resources\WorldCupFixtures\Pages\ViewWorldCupFixture;
use App\Filament\Resources\WorldCupFixtures\Schemas\WorldCupFixtureForm;
use App\Filament\Resources\WorldCupFixtures\Schemas\WorldCupFixtureInfolist;
use App\Filament\Resources\WorldCupFixtures\Tables\WorldCupFixturesTable;
use App\Models\WorldCupFixture;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorldCupFixtureResource extends Resource
{
    protected static ?string $model = WorldCupFixture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return WorldCupFixtureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorldCupFixtureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorldCupFixturesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorldCupFixtures::route('/'),
            'create' => CreateWorldCupFixture::route('/create'),
            'view' => ViewWorldCupFixture::route('/{record}'),
            // 'edit' => EditWorldCupFixture::route('/{record}/edit'),
        ];
    }
}
