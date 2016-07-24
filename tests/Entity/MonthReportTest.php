<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Amount,
    ApplyDay,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Entity\Income,
    Entity\Income\IdentityInterface as IncomeIdentityInterface,
    Entity\FixedCost,
    Entity\FixedCost\IdentityInterface as FixedCostIdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Entity\Expense,
    Entity\Expense\IdentityInterface as ExpenseIdentityInterface,
    Entity\OneOffIncome,
    Entity\OneOffIncome\IdentityInterface as OneOffIncomeIdentityInterface
};

class MonthReportTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $report = new MonthReport(
            $identity = $this->createMock(IdentityInterface::class),
            new \DateTimeImmutable('2016-07')
        );

        $this->assertSame($identity, $report->identity());
        $this->assertSame('2016-07', (string) $report);
        $this->assertSame(0, $report->amount()->value());
        $incomeIdentity = $this->createMock(IncomeIdentityInterface::class);
        $incomeIdentity
            ->method('__toString')
            ->willReturn('foo');
        $this->assertFalse($report->hasIncomeBeenApplied($incomeIdentity));
        $income = new Income(
            $incomeIdentity,
            'foo',
            new Amount(42),
            new ApplyDay(1)
        );
        $this->assertSame(
            $report,
            $report->applyIncome($income)
        );
        $this->assertTrue($report->hasIncomeBeenApplied($incomeIdentity));
        $this->assertSame(42, $report->amount()->value());
        $costIdentity = $this->createMock(FixedCostIdentityInterface::class);
        $costIdentity
            ->method('__toString')
            ->willReturn('foo');
        $this->assertFalse($report->hasFixedCostBeenApplied($costIdentity));
        $this->assertSame($report, $report->markFixedCostAsApplied($costIdentity));
        $this->assertTrue($report->hasFixedCostBeenApplied($costIdentity));
        $this->assertSame(42, $report->amount()->value());
        $this->assertSame(
            $report,
            $report->applyExpense(
                new Expense(
                    $this->createMock(ExpenseIdentityInterface::class),
                    new Amount(4200),
                    $this->createMock(CategoryIdentityInterface::class),
                    new \DateTimeImmutable
                )
            )
        );
        $this->assertSame(-4158, $report->amount()->value());
        $this->assertSame(
            $report,
            $report->applyOneOffIncome(
                new OneOffIncome(
                    $this->createMock(OneOffIncomeIdentityInterface::class),
                    new Amount(5000),
                    new \DateTimeImmutable
                )
            )
        );
        $this->assertSame(842, $report->amount()->value());
    }
}
