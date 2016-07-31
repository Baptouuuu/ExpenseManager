<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyIncome,
    Repository\MonthReportRepositoryInterface,
    Repository\IncomeRepositoryInterface
};

final class ApplyIncomeHandler
{
    private $repository;
    private $incomes;

    public function __construct(
        MonthReportRepositoryInterface $repository,
        IncomeRepositoryInterface $incomes
    ) {
        $this->repository = $repository;
        $this->incomes = $incomes;
    }

    public function __invoke(ApplyIncome $wished)
    {
        $this
            ->repository
            ->get($wished->identity())
            ->applyIncome(
                $this->incomes->get($wished->income())
            );
    }
}
