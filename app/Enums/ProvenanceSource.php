<?php

namespace App\Enums;

enum ProvenanceSource: string
{
    case User = 'user';
    case Agent = 'agent';
    case System = 'system';
}
