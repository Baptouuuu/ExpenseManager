<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyExpense,
    Repository\MonthReportRepositoryInterface,
    Repository\ExpenseRepositoryInterface
};

final class ApplyExpenseHandler
{
    private $repository;
    private $expenses;

    public function __construct(
        MonthReportRepositoryInterface $repository,
        ExpenseRepositoryInterface $expenses
    ) {
        $this->repository = $repository;
        $this->expenses = $expenses;
    }

    public function __invoke(ApplyExpense $wished)
    {
        $this
            ->repository
            ->get($wished->identity())
            ->applyExpense(
                $this->expenses->get($wished->expense())
            );
    }
}
