<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    Budget,
    Budget\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface BudgetRepositoryInterface
{
    public function add(Budget $budget): self;
    public function get(IdentityInterface $identity): Budget;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<Budget>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Budget>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
