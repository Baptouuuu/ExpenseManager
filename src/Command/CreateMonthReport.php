<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\MonthReport\IdentityInterface;

final class CreateMonthReport
{
    private $identity;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        string $date
    ) {
        $this->identity = $identity;
        $this->date = $date;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function date(): string
    {
        return $this->date;
    }
}
