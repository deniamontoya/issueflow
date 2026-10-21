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

    /* un enum tambien puede tener metodos.
     implementados el metodo getLabe() para
     asignar especificamente el texto que se quiere mostyrar al usuario
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Urgent => 'Urgente',
            self::High => 'Alta',
            self::Normal => 'Normal',
            self::Low => 'Baja',
        };
      
    }
}