<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\OneOffIncome;

use ExpenseManager\Entity\OneOffIncome;
use Innmind\Specification\SpecificationInterface;

final class After implements SpecificationInterface
{
    use Composite;

    private $date;

    public function __construct(\DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function isSatisfiedBy(OneOffIncome $income): bool
    {
        return $income->date() >= $this->date;
    }
}
