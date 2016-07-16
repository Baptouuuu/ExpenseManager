<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity\Category;

interface IdentityInterface
{
    public function __toString(): string;
}
