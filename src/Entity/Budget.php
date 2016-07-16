<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Amount,
    Exception\InvalidArgumentException,
    Entity\Budget\IdentityInterface,
    Event\BudgetWasCreated
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};
use Innmind\Immutable\SetInterface;

final class Budget implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;
    private $amount;
    private $categories;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        SetInterface $categories
    ) {
        if (
            empty($name) ||
            (string) $categories->type() !== Category\IdentityInterface::class ||
            $categories->size() === 0
        ) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->categories = $categories;
    }

    public static function create(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        SetInterface $categories
    ): self {
        $budget = new self($identity, $name, $amount, $categories);
        $budget->record(new BudgetWasCreated(
            $identity,
            $name,
            $amount,
            $categories
        ));

        return $budget;
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

    public function categories(): SetInterface
    {
        return $this->categories;
    }
}
