<?php
declare(strict_types = 1);

namespace ExpenseManager\Specification\Expense;

use ExpenseManager\Entity\Expense;
use Innmind\Specification\{
    CompositeInterface,
    Operator,
    SpecificationInterface
};

final class And implements CompositeInterface
{
    use Composite;

    private $left;
    private $right;
    private $operator;

    public function __construct(
        SpecificationInterface $left,
        SpecificationInterface $right
    ) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = new Operator(Operator::AND);
    }

    /**
     * {@inheritdoc}
     */
    public function left(): SpecificationInterface
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    public function right(): SpecificationInterface
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function operator(): Operator
    {
        return $this->operator;
    }

    public function isSatisfiedBy(Expense $expense): bool
    {
        return $this->left->isSatisfiedBy($expense) && $this->right->isSatisfiedBy($expense);
    }
}
