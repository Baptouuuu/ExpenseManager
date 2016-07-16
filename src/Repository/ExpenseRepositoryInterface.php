<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    Expense,
    Expense\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface ExpenseRepositoryInterface
{
    public function add(Expense $expense): self;
    public function get(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<Expense>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Expense>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
