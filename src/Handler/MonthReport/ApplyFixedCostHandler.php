<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyFixedCost,
    Repository\MonthReportRepositoryInterface,
    Repository\FixedCostRepositoryInterface
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
        $this
            ->repository
            ->get($wished->identity())
            ->applyFixedCost(
                $this->costs->get($wished->fixedCost())
            );
    }
}
