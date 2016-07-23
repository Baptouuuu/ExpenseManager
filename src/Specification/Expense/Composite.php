<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\Expense;

use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    NotInterface
};

trait Composite
{
    public function and(SpecificationInterface $spec): CompositeInterface
    {
        return new And($this, $spec);
    }

    public function or(SpecificationInterface $spec): CompositeInterface
    {
        return new Or($this, $spec);
    }

    public function not(): NotInterface
    {
        return new Not($this);
    }
}
