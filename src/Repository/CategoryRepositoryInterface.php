<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    Category,
    Category\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface CategoryRepositoryInterface
{
    public function add(Category $category): self;
    public function get(IdentityInterface $identity): Category;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<Category>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Category>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
