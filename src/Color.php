<?php
declare(strict_types = 1);

namespace ExpenseManager;

use ExpenseManager\Exception\InvalidArgumentException;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class Color
{
    private static $choices;
    private $color;

    public function __construct(string $color)
    {
        if (!self::choices()->contains($color)) {
            throw new InvalidArgumentException;
        }

        $this->color = $color;
    }

    public function __toString(): string
    {
        return $this->color;
    }

    public static function choices(): SetInterface
    {
        if (self::$choices === null) {
            self::$choices = (new Set('string'))
                ->add('red')
                ->add('yellow')
                ->add('green')
                ->add('blue')
                ->add('magenta')
                ->add('cyan')
                ->add('gray')
                ->add('white');
        }

        return self::$choices;
    }
}
