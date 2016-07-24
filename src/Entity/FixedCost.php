<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Entity\FixedCost\IdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Amount,
    ApplyDay,
    Exception\InvalidArgumentException,
    Event\FixedCostWasCreated
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class FixedCost implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;
    private $amount;
    private $applyDay;
    private $category;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay,
        CategoryIdentityInterface $category
    ) {
        if (empty($name) || $amount->value() < 0) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->applyDay = $applyDay;
        $this->category = $category;
    }

    public static function create(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay,
        CategoryIdentityInterface $category
    ): self {
        $self = new self($identity, $name, $amount, $applyDay, $category);
        $self->record(new FixedCostWasCreated(
            $identity,
            $name,
            $amount,
            $applyDay,
            $category
        ));

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

    public function category(): CategoryIdentityInterface
    {
        return $this->category;
    }
}
