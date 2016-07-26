<?php
declare(strict_types = 1);

namespace ExpenseManager\Event\MonthReport;

use ExpenseManager\Entity\{
    MonthReport\IdentityInterface,
    Expense\IdentityInterface as Expense
};

final class ExpenseHasBeenApplied
{
    private $identity;
    private $expense;

    public function __construct(
        IdentityInterface $identity,
        Expense $expense
    ) {
        $this->identity = $identity;
        $this->expense = $expense;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function expense(): Expense
    {
        return $this->expense;
    }
}
