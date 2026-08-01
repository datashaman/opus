<?php

namespace App\Enums;

enum LifecycleState: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Deprecated = 'deprecated';
    case Archived = 'archived';
}
