<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Color,
    Entity\Category\IdentityInterface,
    Event\CategoryWasCreated,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Category implements ContainsRecordedEventsInterface
{
    use EventRecorder;

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

    public static function create(
        IdentityInterface $identity,
        string $name,
        Color $color
    ): self {
        $category = new self($identity, $name, $color);
        $category->record(new CategoryWasCreated($identity, $name, $color));

        return $category;
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
