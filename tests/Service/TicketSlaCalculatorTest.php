<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Model\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use App\Service\TicketSlaCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class TicketSlaCalculatorTest extends TestCase
{
    #[DataProvider('priorityHoursProvider')]
    public function test_it_returns_the_correct_hours_for_each_priority(
        TicketPriority $priority,
        int $expectedHours
    ): void {
        $calculator = new TicketSlaCalculator();

        $ticket = new Ticket(
            id: 'INC-1001',
            title: 'Incidencia de prueba',
            priority: $priority,
            status: TicketStatus::Open,
        );

        self::assertSame(
            $expectedHours,
            $calculator->getResponseTargetHours($ticket)
        );
    }

    public static function priorityHoursProvider(): iterable
    {
        yield 'urgente: 4 horas' => [TicketPriority::Urgent, 4];
        yield 'alta: 8 horas' => [TicketPriority::High, 8];
        yield 'normal: 24 horas' => [TicketPriority::Normal, 24];
        yield 'baja: 72 horas' => [TicketPriority::Low, 72];
    }
}