<?php

namespace App\Enums;

enum StatusDataKehadiran: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Late = 'late';
}
