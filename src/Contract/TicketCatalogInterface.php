<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\Ticket;
/*
 * Contrato que define qué necesita mi aplicación de cualquier catálogo
 * de tickets.
 * La interfaz describe qué operaciones deben existir, pero no cómo se llevan
 * a cabo.
 */

interface TicketCatalogInterface
{
    /* Método que devuelva un array con todas las incidencias */
    public function getTickets(): array;

    /* Método que busca una incidencia en el listado según un
  código proporcionado, y que devuelve null si no existe */
public function findBycode(string $code): ?Ticket;
}