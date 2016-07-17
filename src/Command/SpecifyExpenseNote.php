<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\Expense\IdentityInterface;

final class SpecifyExpenseNote
{
    private $identity;
    private $note;

    public function __construct(IdentityInterface $identity, string $note)
    {
        $this->identity = $identity;
        $this->note = $note;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function note(): string
    {
        return $this->note;
    }
}
