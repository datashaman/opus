<?php

namespace App\Enums;

enum MotifType: string
{
    case Motif = 'motif';
    case Theme = 'theme';
    case Phrase = 'phrase';
    case Gesture = 'gesture';
    case RhythmicCell = 'rhythmic_cell';
}
