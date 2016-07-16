<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Amount,
    Exception\InvalidArgumentException,
    Entity\Budget\IdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface
};
use Innmind\Immutable\SetInterface;

final class BudgetWasCreated
{
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
            (string) $categories->type() !== CategoryIdentityInterface::class ||
            $categories->size() === 0
        ) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->categories = $categories;
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
