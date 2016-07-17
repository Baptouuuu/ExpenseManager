<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateBudget,
    Repository\BudgetRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\Budget,
    Entity\Category\IdentityInterface as Category,
    Amount,
    Exception\CantUseUnknownCategoryException
};
use Innmind\Immutable\Set;

final class CreateBudgetHandler
{
    private $repository;
    private $categories;

    public function __construct(
        BudgetRepositoryInterface $repository,
        CategoryRepositoryInterface $categories
    ) {
        $this->repository = $repository;
        $this->categories = $categories;
    }

    public function __invoke(CreateBudget $wished)
    {
        $categories = new Set(Category::class);

        foreach ($wished->categories() as $category) {
            if (!$this->categories->has($category)) {
                throw new CantUseUnknownCategoryException;
            }

            $categories = $categories->add($category);
        }

        $this->repository->add(
            Budget::create(
                $wished->identity(),
                $wished->name(),
                new Amount($wished->amount()),
                $categories
            )
        );
    }
}
