<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    OneOffIncome,
    OneOffIncome\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface OneOffIncomeRepositoryInterface
{
    public function add(OneOffIncome $oneOffIncome): self;
    public function get(IdentityInterface $identity): OneOffIncome;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<OneOffIncome>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<OneOffIncome>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
