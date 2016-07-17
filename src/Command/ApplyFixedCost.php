<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\{
    FixedCost\IdentityInterface,
    Expense\IdentityInterface as Expense
};

final class ApplyFixedCost
{
    private $identity;
    private $expenseIdentity;

    public function __construct(
        IdentityInterface $identity,
        Expense $expenseIdentity
    ) {
        $this->identity = $identity;
        $this->expenseIdentity = $expenseIdentity;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function expenseIdentity(): Expense
    {
        return $this->expenseIdentity;
    }
}
