<?php

declare(strict_types=1);

namespace App\Api\Model;

use App\Model\TicketPriority;
use App\Model\TicketStatus;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/** Payload parcial para PATCH /api/tickets/{code}. */
final readonly class TicketUpdatePayload
{
    public function __construct(
        public array $values,
    ) {}

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        foreach (array_diff(array_keys($this->values), ['title', 'priority', 'status']) as $field) {
            $context->buildViolation(sprintf('El campo "%s" no se puede modificar.', (string) $field))
                ->atPath((string) $field)
                ->addViolation();
        }

        if (array_key_exists('title', $this->values)) {
            $title = $this->values['title'];
            if (!is_string($title) || trim($title) === '') {
                $context->buildViolation('El título no puede estar vacío y debe ser texto.')
                    ->atPath('title')->addViolation();
            } elseif (iconv_strlen(trim($title), 'UTF-8') > 180) {
                $context->buildViolation('El título no puede superar los 180 caracteres.')
                    ->atPath('title')->addViolation();
            }
        }

        foreach (['priority' => TicketPriority::class, 'status' => TicketStatus::class] as $field => $enum) {
            if (array_key_exists($field, $this->values)
                && (!is_string($this->values[$field]) || $enum::tryFrom($this->values[$field]) === null)) {
                $context->buildViolation(sprintf('El valor de %s no es válido.', $field))
                    ->atPath($field)->addViolation();
            }
        }
    }
}
