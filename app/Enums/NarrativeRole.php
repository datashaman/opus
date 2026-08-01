<?php

namespace App\Enums;

enum NarrativeRole: string
{
    case Introduction = 'introduction';
    case Escalation = 'escalation';
    case Release = 'release';
    case Interruption = 'interruption';
    case Return = 'return';
}
