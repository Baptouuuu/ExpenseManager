<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\OneOffIncome;

use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    NotInterface
};

trait Composite
{
    public function and(SpecificationInterface $spec): CompositeInterface
    {
        return new AndSpecification($this, $spec);
    }

    public function or(SpecificationInterface $spec): CompositeInterface
    {
        return new OrSpecification($this, $spec);
    }

    public function not(): NotInterface
    {
        return new Not($this);
    }
}
