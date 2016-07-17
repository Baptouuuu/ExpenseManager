<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateExpense,
    Repository\ExpenseRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\Expense,
    Amount,
    Exception\CantUseUnknownCategoryException
};

final class CreateExpenseHandler
{
    private $repository;
    private $categories;

    public function __construct(
        ExpenseRepositoryInterface $repository,
        CategoryRepositoryInterface $categories
    ) {
        $this->repository = $repository;
        $this->categories = $categories;
    }

    public function __invoke(CreateExpense $wished)
    {
        if (!$this->categories->has($wished->category())) {
            throw new CantUseUnknownCategoryException;
        }

        $this->repository->add(
            Expense::create(
                $wished->identity(),
                new Amount($wished->amount()),
                $wished->category(),
                new \DateTimeImmutable($wished->date())
            )
        );
    }
}
