<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity\Expense;

interface IdentityInterface
{
    public function __toString(): string;
}
