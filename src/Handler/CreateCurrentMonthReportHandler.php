<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateCurrentMonthReport,
    Repository\MonthReportRepositoryInterface,
    Entity\MonthReport
};

final class CreateCurrentMonthReportHandler
{
    private $repository;

    public function __construct(MonthReportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateCurrentMonthReport $wished)
    {
        $this->repository->add(
            MonthReport::create(
                $wished->identity(),
                new \DateTimeImmutable(date('Y-m'))
            )
        );
    }
}
