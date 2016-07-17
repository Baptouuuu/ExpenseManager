<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\{
    Expense\IdentityInterface,
    Category\IdentityInterface as Category
};

final class CreateExpense
{
    private $identity;
    private $amount;
    private $category;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        int $amount,
        Category $category,
        string $date
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

    public function amount(): int
    {
        return $this->amount;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function date(): string
    {
        return $this->date;
    }
}
