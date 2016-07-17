<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Entity\Income\IdentityInterface,
    Amount,
    ApplyDay
};

final class IncomeWasCreated
{
    private $identity;
    private $name;
    private $amount;
    private $applyDay;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->applyDay = $applyDay;
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

    public function applyDay(): ApplyDay
    {
        return $this->applyDay;
    }
}
