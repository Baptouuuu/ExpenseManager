<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Entity\Expense\IdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Amount
};

final class ExpenseWasCreated
{
    private $identity;
    private $amount;
    private $category;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        Amount $amount,
        CategoryIdentityInterface $category,
        \DateTimeImmutable $date
    ) {
        $this->identity = $identity;
        $this->amount = $amount;
        $this->category = $category;
        $this->date = $date;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function category(): CategoryIdentityInterface
    {
        return $this->category;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
