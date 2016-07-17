<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Entity\OneOffIncome\IdentityInterface,
    Amount
};

final class OneOffIncomeWasCreated
{
    private $identity;
    private $amount;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        Amount $amount,
        \DateTimeImmutable $date
    ) {
        $this->identity = $identity;
        $this->amount = $amount;
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

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
