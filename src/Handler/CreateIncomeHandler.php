<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateIncome,
    Repository\IncomeRepositoryInterface,
    Entity\Income,
    Amount,
    ApplyDay
};

final class CreateIncomeHandler
{
    private $repository;

    public function __construct(IncomeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateIncome $wished)
    {
        $this->repository->add(
            Income::create(
                $wished->identity(),
                $wished->name(),
                new Amount($wished->amount()),
                new ApplyDay($wished->applyDay())
            )
        );
    }
}
