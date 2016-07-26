<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyOneOffIncome,
    Repository\MonthReportRepositoryInterface,
    Repository\OneOffIncomeRepositoryInterface
};

final class ApplyOneOffIncomeHandler
{
    private $repository;
    private $incomes;

    public function __construct(
        MonthReportRepositoryInterface $repository,
        OneOffIncomeRepositoryInterface $incomes
    ) {
        $this->repository = $repository;
        $this->incomes = $incomes;
    }

    public function __invoke(ApplyOneOffIncome $wished)
    {
        $this
            ->repository
            ->get($wished->identity())
            ->applyOneOffIncome(
                $this->incomes->get($wished->oneOffIncome())
            );
    }
}
