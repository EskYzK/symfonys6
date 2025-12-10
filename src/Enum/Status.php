<?php

namespace App\Enum;

enum Status: string
{
    case draft = 'draft';
    case submitted = 'submitted';
}