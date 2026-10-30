<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\TicketCatalogInterface;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

/**
 * Repository Doctrine de Ticket.
 *  Extiende ServiceEntityRepository de Doctrine, el cual tiene el metodo para hacer peticiones a la base de datos
 */
/* Al usar AsAlias, por defecto, el contenedor de servicios de Symfony inyectará esta clase
    cuando se solicite una implementación de TicketCatalogInterface */
#[AsAlias(TicketCatalogInterface::class)]
final class TicketRepository extends ServiceEntityRepository implements TicketCatalogInterface
{
	public function __construct(ManagerRegistry $registry)
    {
        // El repositorio queda especializado para la Entity Ticktet
        parent::__construct($registry, Ticket::class);
    }

    /* Método que devuelva un array con todas las incidencias */
    public function getTickets(): array
    {
         /*
 * El método findBy() de ServiceEntityRepository, devuelve una lista de la base de datos.
 * El único argumento es un array de condiciones, si lo dejo vacío, significa que no hay condiciones.
 * El segundo argumento indica el orden en que voy a recibir el listado.
 */
    return $this->findBy([], ['id' => 'ASC']);
    }

    /* Método que busca una incidencia en el listado según un
    code proporcionado, y que devuelva null si no existe */
    public function findByCode(string $code): ?Ticket
    {


    return $this->findOneBy(['code' => $code]);
    }
}