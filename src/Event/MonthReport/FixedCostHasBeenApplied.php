<?php
declare(strict_types = 1);

namespace ExpenseManager\Event\MonthReport;

use ExpenseManager\Entity\{
    MonthReport\IdentityInterface,
    FixedCost\IdentityInterface as FixedCost
};

final class FixedCostHasBeenApplied
{
    private $identity;
    private $fixedCost;

    public function __construct(
        IdentityInterface $identity,
        FixedCost $fixedCost
    ) {
        $this->identity = $identity;
        $this->fixedCost = $fixedCost;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function fixedCost(): FixedCost
    {
        return $this->fixedCost;
    }
}
