<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyOneOffIncome,
    Handler\MonthReport\ApplyOneOffIncomeHandler,
    Amount,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Entity\OneOffIncome,
    Entity\OneOffIncome\IdentityInterface as OneOffIncomeIdentityInterface,
    Repository\MonthReportRepositoryInterface,
    Repository\OneOffIncomeRepositoryInterface
};

class ApplyOneOffIncomeHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new ApplyOneOffIncomeHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $incomes = $this->createMock(OneOffIncomeRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $incomes
            ->method('get')
            ->willReturn($income = new OneOffIncome(
                $this->createMock(OneOffIncomeIdentityInterface::class),
                new Amount(42),
                new \DateTimeImmutable('2016-07')
            ));

        $this->assertNull($handler(
            new ApplyOneOffIncome(
                $report->identity(),
                $income->identity()
            )
        ));
        $this->assertSame(42, $report->amount()->value());
    }
}
