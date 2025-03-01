<?php

namespace App\Enum;

enum PillIntakeStatus: string
{
    case TAKEN = 'taken';
    case ADJUSTED = 'adjusted';
    case SKIPPED = 'skipped';
    case PENDING = 'pending';
}
