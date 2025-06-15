<?php

namespace App\Enums;

// app/Enums/NoteType.php
enum NoteType: string
{
    case INFO = 'info';
    case TASK = 'task';
    case ALERT = 'alert';
}

