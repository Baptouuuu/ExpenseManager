<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\Income\IdentityInterface;

final class CreateIncome
{
    private $identity;
    private $name;
    private $amount;
    private $applyDay;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        int $amount,
        int $applyDay
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

    public function amount(): int
    {
        return $this->amount;
    }

    public function applyDay(): int
    {
        return $this->applyDay;
    }
}
