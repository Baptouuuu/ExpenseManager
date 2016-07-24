<?php
declare(strict_types = 1);

namespace ExpenseManager;

use ExpenseManager\Exception\InvalidArgumentException;

final class Amount
{
    private $cents;

    public function __construct(int $cents)
    {
        $this->cents = $cents;
    }

    public function value(): int
    {
        return $this->cents;
    }

    public function add(self $amount): self
    {
        return new self($this->value() + $amount->value());
    }

    public function subtract(self $amount): self
    {
        return new self($this->value() - $amount->value());
    }
}
