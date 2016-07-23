<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\Expense;

use ExpenseManager\Entity\Expense;
use Innmind\Specification\SpecificationInterface;

final class After implements SpecificationInterface
{
    use Composite;

    private $date;

    public function __construct(\DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function isSatisfiedBy(Expense $expense): bool
    {
        return $expense->date() >= $this->date;
    }
}
