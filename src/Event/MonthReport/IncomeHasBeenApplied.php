<?php
declare(strict_types = 1);

namespace ExpenseManager\Event\MonthReport;

use ExpenseManager\Entity\{
    MonthReport\IdentityInterface,
    Income\IdentityInterface as Income
};

final class IncomeHasBeenApplied
{
    private $identity;
    private $income;

    public function __construct(
        IdentityInterface $identity,
        Income $income
    ) {
        $this->identity = $identity;
        $this->income = $income;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function income(): Income
    {
        return $this->income;
    }
}
