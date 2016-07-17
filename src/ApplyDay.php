<?php
declare(strict_types = 1);

namespace ExpenseManager;

use ExpenseManager\Exception\InvalidArgumentException;
use Innmind\Immutable\NumericRange;

final class ApplyDay
{
    private $value;
    private static $available;

    public function __construct(int $day)
    {
        if (
            $day < self::available()->start() ||
            $day > self::available()->end()
        ) {
            throw new InvalidArgumentException;
        }

        $this->value = $day;
    }

    public function value(): int
    {
        return $this->value;
    }

    public static function available(): NumericRange
    {
        if (self::$available === null) {
            self::$available = new NumericRange(1, 28, 1);
        }

        return self::$available;
    }
}
