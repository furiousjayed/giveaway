<?php

namespace App\Enums;

enum GiveawayStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case WINNERS_SELECTED = 'winners_selected';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Active',
            self::CLOSED => 'Closed',
            self::WINNERS_SELECTED => 'Winners Selected',
        };
    }
}
