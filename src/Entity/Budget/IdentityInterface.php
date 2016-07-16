<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity\Budget;

interface IdentityInterface
{
    public function __toString(): string;
}
