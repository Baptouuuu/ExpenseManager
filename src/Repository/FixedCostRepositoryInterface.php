<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    FixedCost,
    FixedCost\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface FixedCostRepositoryInterface
{
    public function add(FixedCost $fixedCost): self;
    public function get(IdentityInterface $identity): FixedCost;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<FixedCost>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<FixedCost>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
