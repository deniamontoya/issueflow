<?php

declare(strict_types=1);

namespace App\Form\Model;

Use App\Entity\Category;
Use App\Entity\User;
Use App\Entity\Ticket;
Use App\Model\TicketPriority;
Use App\Model\TicketStatus;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Datos de entrada para crear una incidencia desde un formulario.
 * Su responsabilidad es recibir los datos que Symfony Form transforma desde la
 * Request (petición), o sea, los datos que el usuario ha enviado desde el formulario.
 * También aplica validaciones a esos datos, y hace el mapeo hacia la entidad Ticket.
 * Usamos propiedades publicas deliberadamente, este objeto es uan estructura simple
 * de entrada
 * los valores por defecto de los atributos correspondan a los valores iniciales
 * del formulario
 */
final class TicketCreateData
{
    #[Assert\NotBlank(
        message: 'el codigo es obligatorio',
        normalizer: 'trim'
    )]
    #[Assert\Regex(
        pattern: '/^INC-\d{4}$/',
        message: 'el codigo debe tener el formato INC-0000',
        normalizer: 'trim'
    )]
public string $code = '';

#[Assert\NotBlank(
    message: 'El título es obligatorio',
    normalizer: 'trim'
)]
#[Assert\Length(
    max: 180,
    maxMessage: 'El título no puede superar los 180 caracteres',
    normalizer: 'trim'
)]
public string $title = '';

public TicketPriority $priority = TicketPriority::Normal;
public TicketStatus $status = TicketStatus::Open;
public ?Category $category = null;
public ?User $assignee = null;

/**
 * metodo para mapear TicketCreateData a Ticket
 */
public function mapToTicket(): Ticket
{
    $ticket = new Ticket(
        code: $this->code,
        title: $this->title,
        priority: $this->priority,
        status: $this->status,
    );
    $ticket->setCategory($this->category);
    $ticket->assignTo($this->assignee);
    
         return $ticket;
  }
}



