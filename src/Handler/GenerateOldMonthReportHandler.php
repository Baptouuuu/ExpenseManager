<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Repository\MonthReportRepositoryInterface,
    Repository\IncomeRepositoryInterface,
    Repository\FixedCostRepositoryInterface,
    Command\GenerateOldMonthReport,
    Exception\MonthReportDateNotAfterThisMonthException,
    Entity\MonthReport,
    Entity\Income,
    Entity\FixedCost
};

final class GenerateOldMonthReportHandler
{
    private $repository;
    private $incomes;
    private $fixedCosts;

    public function __construct(
        MonthReportRepositoryInterface $repository,
        IncomeRepositoryInterface $incomes,
        FixedCostRepositoryInterface $fixedCosts
    ) {
        $this->repository = $repository;
        $this->incomes = $incomes;
        $this->fixedCosts = $fixedCosts;
    }

    public function __invoke(GenerateOldMonthReport $wished)
    {
        $thisMonth = new \DateTimeImmutable(date('Y-m-01 00:00:00'));
        $date = new \DateTimeImmutable($wished->date());

        if ($date > $thisMonth) {
            throw new MonthReportDateNotAfterThisMonthException;
        }

        $report = MonthReport::create($wished->identity(), $date);
        $this
            ->incomes
            ->all()
            ->reduce(
                $report,
                function(MonthReport $report, Income $income): MonthReport {
                    return $report->applyIncome($income);
                }
            );
        $this
            ->fixedCosts
            ->all()
            ->reduce(
                $report,
                function(MonthReport $report, FixedCost $cost): MonthReport {
                    return $report->applyFixedCost($cost);
                }
            );
        $this->repository->add($report);
    }
}
