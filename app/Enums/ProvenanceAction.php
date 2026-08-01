<?php

namespace App\Enums;

enum ProvenanceAction: string
{
    case Created = 'created';
    case Modified = 'modified';
    case Transformed = 'transformed';
    case Absorbed = 'absorbed';
    case Reverted = 'reverted';
    case Deprecated = 'deprecated';
}
