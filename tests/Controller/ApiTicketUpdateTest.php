<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Ticket;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiTicketUpdateTest extends WebTestCase
{
    private function createTicketFixture(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
        $entityManager->persist(new Ticket('INC-1001', 'Título inicial', TicketPriority::Normal, TicketStatus::Open));
        $entityManager->persist(new Ticket('INC-1002', 'Otra incidencia', TicketPriority::Low, TicketStatus::Open));
        $entityManager->flush();
    }

    public function test_patch_updates_partial_fields_and_persists_them(): void
    {
        $client = static::createClient();
        $this->createTicketFixture();
        $client->request('PATCH', '/api/tickets/INC-1001', server: ['CONTENT_TYPE' => 'application/json'], content: '{"title":"  Título actualizado  "}');
        self::assertResponseIsSuccessful();
        self::assertSame('Título actualizado', json_decode($client->getResponse()->getContent(), true)['ticket']['title']);

        $client->request('PATCH', '/api/tickets/INC-1001', server: ['CONTENT_TYPE' => 'application/json'], content: '{"priority":"high","status":"in_progress"}');
        self::assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true)['ticket'];
        self::assertSame('high', $data['priority']);
        self::assertSame('in_progress', $data['status']);
        self::assertSame('Título actualizado', $data['title']);

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->clear();
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy(['code' => 'INC-1001']);
        self::assertSame('Título actualizado', $ticket->getTitle());
        self::assertSame(TicketPriority::High, $ticket->getPriority());
        self::assertSame(TicketStatus::InProgress, $ticket->getStatus());
    }

    public function test_patch_rejects_invalid_codes_payloads_and_json(): void
    {
        $client = static::createClient();
        $this->createTicketFixture();
        foreach ([
            ['/api/tickets/nope', '{}', 400],
            ['/api/tickets/INC-9999', '{}', 404],
            ['/api/tickets/INC-1001', '{', 400],
            ['/api/tickets/INC-1001', '{"title":"  "}', 422],
            ['/api/tickets/INC-1001', '{"title":"'.str_repeat('a', 181).'"}', 422],
            ['/api/tickets/INC-1001', '{"priority":"bad"}', 422],
            ['/api/tickets/INC-1001', '{"status":"bad"}', 422],
            ['/api/tickets/INC-1001', '{"code":"INC-2000"}', 422],
            ['/api/tickets/INC-1001', '{"title":null}', 422],
            ['/api/tickets/INC-1001', '{"priority":null}', 422],
            ['/api/tickets/INC-1001', '{"status":null}', 422],
        ] as [$uri, $content, $status]) {
            $client->request('PATCH', $uri, server: ['CONTENT_TYPE' => 'application/json'], content: $content);
            self::assertResponseStatusCodeSame($status);
        }
    }

    public function test_delete_removes_ticket_and_preserves_other_tickets(): void
    {
        $client = static::createClient();
        $this->createTicketFixture();

        $client->request('DELETE', '/api/tickets/INC-1001');
        self::assertResponseStatusCodeSame(204);
        self::assertSame('', $client->getResponse()->getContent());

        $client->request('GET', '/api/tickets/INC-1001');
        self::assertResponseStatusCodeSame(404);

        $client->request('DELETE', '/api/tickets/INC-1001');
        self::assertResponseStatusCodeSame(404);

        $client->request('DELETE', '/api/tickets/invalid');
        self::assertResponseStatusCodeSame(400);

        $client->request('GET', '/api/tickets/INC-1002');
        self::assertResponseIsSuccessful();
        self::assertSame('INC-1002', json_decode($client->getResponse()->getContent(), true)['ticket']['code']);
    }
}
