<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateMonthReport,
    Repository\MonthReportRepositoryInterface,
    Entity\MonthReport
};

final class CreateMonthReportHandler
{
    private $repository;

    public function __construct(MonthReportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateMonthReport $wished)
    {
        $this->repository->add(
            new MonthReport(
                $wished->identity(),
                new \DateTimeImmutable($wished->date())
            )
        );
    }
}
