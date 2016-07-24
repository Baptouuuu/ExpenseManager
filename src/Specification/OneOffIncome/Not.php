<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\OneOffIncome;

use ExpenseManager\Entity\OneOffIncome;
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

    public function isSatisfiedBy(OneOffIncome $income): bool
    {
        return !$this->spec->isSatisfiedBy($income);
    }
}
