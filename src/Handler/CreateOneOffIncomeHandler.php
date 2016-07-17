<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateOneOffIncome,
    Repository\OneOffIncomeRepositoryInterface,
    Entity\OneOffIncome,
    Amount
};

final class CreateOneOffIncomeHandler
{
    private $repository;

    public function __construct(OneOffIncomeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateOneOffIncome $wished)
    {
        $this->repository->add(
            OneOffIncome::create(
                $wished->identity(),
                new Amount($wished->amount()),
                new \DateTimeImmutable($wished->date())
            )
        );
    }
}
