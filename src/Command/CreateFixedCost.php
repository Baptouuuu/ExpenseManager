<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\{
    FixedCost\IdentityInterface,
    Category\IdentityInterface as Category
};

final class CreateFixedCost
{
    private $identity;
    private $name;
    private $amount;
    private $applyDay;
    private $category;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        int $amount,
        int $applyDay,
        Category $category
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->amount = $amount;
        $this->applyDay = $applyDay;
        $this->category = $category;
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

    public function applyDay(): int
    {
        return $this->applyDay;
    }

    public function category(): Category
    {
        return $this->category;
    }
}
