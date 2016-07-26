<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyFixedCost,
    Repository\MonthReportRepositoryInterface,
    Repository\FixedCostRepositoryInterface,
    Exception\CantApplyFixedCostTodayException
};

final class ApplyFixedCostHandler
{
    private $repository;
    private $costs;

    public function __construct(
        MonthReportRepositoryInterface $repository,
        FixedCostRepositoryInterface $costs
    ) {
        $this->repository = $repository;
        $this->costs = $costs;
    }

    public function __invoke(ApplyFixedCost $wished)
    {
        $cost = $this->costs->get($wished->fixedCost());

        if ($cost->applyDay()->value() !== (int) (new \DateTime)->format('j')) {
            throw new CantApplyFixedCostTodayException;
        }

        $this
            ->repository
            ->get($wished->identity())
            ->applyFixedCost($cost);
    }
}
