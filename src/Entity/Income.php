<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Entity\Income\IdentityInterface,
    Amount,
    ApplyDay,
    Exception\InvalidArgumentException,
    Event\IncomeWasCreated
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Income implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;
    private $amount;
    private $applyDay;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay
    ) {
        if (empty($name) || $amount->value() < 0) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->applyDay = $applyDay;
    }

    public static function create(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay
    ): self {
        $self = new self($identity, $name, $amount, $applyDay);
        $self->record(new IncomeWasCreated($identity, $name, $amount, $applyDay));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function applyDay(): ApplyDay
    {
        return $this->applyDay;
    }
}
