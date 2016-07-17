<?php
declare(strict_types = 1);

namespace ExpenseManager\Command;

use ExpenseManager\Entity\Category\IdentityInterface;

final class CreateCategory
{
    private $identity;
    private $name;
    private $color;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        string $color
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->color = $color;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function color()
    {
        return $this->color;
    }
}
