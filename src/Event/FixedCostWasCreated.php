<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Entity\FixedCost\IdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Amount,
    ApplyDay
};

final class FixedCostWasCreated
{
    private $identity;
    private $name;
    private $amount;
    private $applyDay;
    private $category;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Amount $amount,
        ApplyDay $applyDay,
        CategoryIdentityInterface $category
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

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function applyDay(): ApplyDay
    {
        return $this->applyDay;
    }

    public function category(): CategoryIdentityInterface
    {
        return $this->category;
    }
}
