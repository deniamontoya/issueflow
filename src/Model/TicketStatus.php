<?php

declare(strict_types=1);

namespace App\Model;

/*
estados permitidad por el sistema issueflow

el sistema permitido solo tres prioridades: 'urgent',
'high', 'normal', 'low'
*/
enum TicketStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Closed = 'closed';
}