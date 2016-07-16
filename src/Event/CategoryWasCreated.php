<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\{
    Color,
    Entity\Category\IdentityInterface,
    Exception\InvalidArgumentException
};

final class CategoryWasCreated
{
    private $identity;
    private $name;
    private $color;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        Color $color
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException;
        }

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

    public function color(): Color
    {
        return $this->color;
    }
}
