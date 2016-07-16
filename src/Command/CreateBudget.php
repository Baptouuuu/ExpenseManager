<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\Budget\IdentityInterface;

final class CreateBudget
{
    private $identity;
    private $name;
    private $amount;
    private $categories;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        int $amount,
        array $categories
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->categories = $categories;
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

    public function categories(): array
    {
        return $this->categories;
    }
}
