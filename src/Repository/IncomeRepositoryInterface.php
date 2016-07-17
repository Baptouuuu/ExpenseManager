<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    Income,
    Income\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface IncomeRepositoryInterface
{
    public function add(Income $income): self;
    public function get(IdentityInterface $identity): Income;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<Income>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Income>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
