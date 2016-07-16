<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateBudget,
    Repository\BudgetRepositoryInterface,
    Entity\Budget,
    Entity\Category\IdentityInterface as Category,
    Amount
};
use Innmind\Immutable\Set;

final class CreateBudgetHandler
{
    private $repository;

    public function __construct(BudgetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateBudget $wished)
    {
        $categories = new Set(Category::class);

        foreach ($wished->categories() as $category) {
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
