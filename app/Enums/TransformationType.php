<?php

namespace App\Enums;

enum TransformationType: string
{
    case Inversion = 'inversion';
    case Transposition = 'transposition';
    case Augmentation = 'augmentation';
    case Diminution = 'diminution';
    case Fragmentation = 'fragmentation';
    case Reharmonization = 'reharmonization';
}
