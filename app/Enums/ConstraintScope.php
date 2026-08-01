<?php

namespace App\Enums;

enum ConstraintScope: string
{
    case Global = 'global';
    case Branch = 'branch';
    case Section = 'section';
    case Entity = 'entity';
}
