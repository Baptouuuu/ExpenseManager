<?php
declare(strict_types = 1);

namespace ExpenseManager\Event\MonthReport;

use ExpenseManager\Entity\{
    MonthReport\IdentityInterface,
    OneOffIncome\IdentityInterface as OneOffIncome
};

final class OneOffIncomeHasBeenApplied
{
    private $identity;
    private $income;

    public function __construct(
        IdentityInterface $identity,
        OneOffIncome $income
    ) {
        $this->identity = $identity;
        $this->income = $income;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function oneOffIncome(): OneOffIncome
    {
        return $this->income;
    }
}
