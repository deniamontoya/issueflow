<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\InMemoryTicketCatalog;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryTicketCatalogTest extends TestCase
{
    #[Test]
    public function it_returns_all_tickets(): void
    {
        $catalog = new InMemoryTicketCatalog();

        $tickets = $catalog->getTickets();

        self::assertCount(5, $tickets);
        self::assertSame('INC-1001', $tickets[0]->getId());
    }

    #[Test]
    public function it_finds_a_ticket_by_its_id(): void
    {
        $catalog = new InMemoryTicketCatalog();

        $ticket = $catalog->find('INC-1002');

        self::assertNotNull($ticket);
        self::assertSame('INC-1002', $ticket->getId());
    }

    #[Test]
    public function it_returns_null_when_the_ticket_does_not_exist(): void
    {
        $catalog = new InMemoryTicketCatalog();

        self::assertNull($catalog->find('INC-9999'));
    }
}