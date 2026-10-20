<?php

declare(strict_types=1);

namespace App\Model;

/*
Prioridades permitidad por el sistema issueflow

el sistema permitido solo cuatro prioridades: 'urgent',
'high', 'normal', 'low'
*/
enum TicketPriority: string
{
    case Urgent = 'urgent';
    case High = 'high';
    case Normal = 'normal';
    case Low = 'low';
}