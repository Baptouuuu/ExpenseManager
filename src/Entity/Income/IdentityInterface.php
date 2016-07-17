<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity\Income;

interface IdentityInterface
{
    public function __toString(): string;
}
