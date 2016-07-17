<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateExpense,
    Repository\ExpenseRepositoryInterface,
    Entity\Expense,
    Amount
};

final class CreateExpenseHandler
{
    private $repository;

    public function __construct(ExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateExpense $wished)
    {
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
