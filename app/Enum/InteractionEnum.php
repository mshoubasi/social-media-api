<?php
namespace App\Enum;


enum InteractionEnum : string
{
    case Like   = 'like';
    case Dislike = 'dislike';
    case Funny  = 'funny';
    case Love  = 'love';

}
