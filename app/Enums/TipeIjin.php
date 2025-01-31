<?php

namespace App\Enums;

enum TipeIjin: string
{
    case Cuti = 'cuti';

    case Sakit = 'sakit dengan surat dokter';

    case Ijin = 'ijin potong gaji';
}
