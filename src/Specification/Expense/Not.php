<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\Expense;

use ExpenseManager\Entity\Expense;
use Innmind\Specification\{
    NotInterface,
    SpecificationInterface
};

final class Not implements NotInterface
{
    use Composite;

    private $spec;

    public function __construct(SpecificationInterface $spec)
    {
        $this->spec = $spec;
    }

    /**
     * {@inheritdoc}
     */
    public function specification(): SpecificationInterface
    {
        return $this->spec;
    }

    public function isSatisfiedBy(Expense $expense): bool
    {
        return !$this->spec->isSatisfiedBy($expense);
    }
}
