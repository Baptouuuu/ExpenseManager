<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\MonthReport\IdentityInterface;

final class CreateCurrentMonthReport
{
    private $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }
}
