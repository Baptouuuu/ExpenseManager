<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyIncome,
    Repository\MonthReportRepositoryInterface,
    Repository\IncomeRepositoryInterface,
    Exception\CantApplyIncomeTodayException
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
        $income = $this->incomes->get($wished->income());

        if ($income->applyDay()->value() !== (int) (new \DateTime)->format('j')) {
            throw new CantApplyIncomeTodayException;
        }

        $this
            ->repository
            ->get($wished->identity())
            ->applyIncome($income);
    }
}
