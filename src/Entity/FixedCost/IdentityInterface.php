<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity\FixedCost;

interface IdentityInterface
{
    public function __toString(): string;
}
