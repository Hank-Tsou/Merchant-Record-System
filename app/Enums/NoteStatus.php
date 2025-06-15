<?php

namespace App\Enums;

// app/Enums/NoteStatus.php
enum NoteStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case IN_PROGRESS = 'in_progress';
}
