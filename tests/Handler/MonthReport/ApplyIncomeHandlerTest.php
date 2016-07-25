<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyIncome,
    Handler\MonthReport\ApplyIncomeHandler,
    Amount,
    ApplyDay,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Entity\Income,
    Entity\Income\IdentityInterface as IncomeIdentityInterface,
    Repository\MonthReportRepositoryInterface,
    Repository\IncomeRepositoryInterface
};

class ApplyIncomeHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $day = (int) (new \DateTime)->format('j');

        if ($day > 28) {
            $this->assertTrue(true, 'Cannot test after the 28th day of the month :(');

            return;
        }

        $handler = new ApplyIncomeHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $incomes = $this->createMock(IncomeRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $incomes
            ->method('get')
            ->willReturn($income = new Income(
                $this->createMock(IncomeIdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day)
            ));

        $this->assertNull($handler(
            new ApplyIncome(
                $report->identity(),
                $income->identity()
            )
        ));
        $this->assertTrue($report->hasIncomeBeenApplied($income->identity()));
    }

    /**
     * @expectedException ExpenseManager\Exception\CantApplyIncomeTodayException
     */
    public function testThrowWhenTryingToApplyIncomeAnotherDayItIsSupposedTo()
    {
        $day = (int) (new \DateTime)->format('j') + 1;

        if ($day > 28) {
            $day = 1;
        }

        $handler = new ApplyIncomeHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $incomes = $this->createMock(IncomeRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $incomes
            ->method('get')
            ->willReturn($income = new Income(
                $this->createMock(IncomeIdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day)
            ));

        $handler(
            new ApplyIncome(
                $report->identity(),
                $income->identity()
            )
        );
    }
}
