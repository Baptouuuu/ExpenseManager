<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\ApplyFixedCost,
    Command\CreateExpense,
    Repository\FixedCostRepositoryInterface,
    Exception\CantApplyFixedCostTodayException
};

final class ApplyFixedCostHandler
{
    private $repository;
    private $createExpenseHandler;

    public function __construct(
        FixedCostRepositoryInterface $repository,
        CreateExpenseHandler $createExpenseHandler
    ) {
        $this->repository = $repository;
        $this->createExpenseHandler = $createExpenseHandler;
    }

    public function __invoke(ApplyFixedCost $wished)
    {
        $cost = $this->repository->get($wished->identity());

        if ($cost->applyDay()->value() !== (int) (new \DateTime)->format('j')) {
            throw new CantApplyFixedCostTodayException;
        }

        call_user_func(
            $this->createExpenseHandler,
            new CreateExpense(
                $wished->expenseIdentity(),
                $cost->amount()->value(),
                $cost->category(),
                'today'
            )
        );
    }
}
