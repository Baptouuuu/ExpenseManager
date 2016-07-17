<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\OneOffIncome\IdentityInterface;

final class CreateOneOffIncome
{
    private $identity;
    private $amount;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        int $amount,
        string $date
    ) {
        $this->identity = $identity;
        $this->amount = $amount;
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

    public function date(): string
    {
        return $this->date;
    }
}
