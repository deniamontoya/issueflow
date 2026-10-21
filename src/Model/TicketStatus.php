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
 /* un enum tambien puede tener metodos.
     implementados el metodo getLabe() para
     asignar especificamente el texto que se quiere mostyrar al usuario
     */
    public function getLabel(): string
    {
        return match($this) {
           self::Open => 'Abierto',
        self::InProgress => 'En progreso',
        self::Closed => 'Cerrado',
        };
    }
}