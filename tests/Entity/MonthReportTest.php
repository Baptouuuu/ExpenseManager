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
    Entity\OneOffIncome\IdentityInterface as OneOffIncomeIdentityInterface,
    Event\MonthReportWasCreated,
    Event\MonthReport\IncomeHasBeenApplied,
    Event\MonthReport\FixedCostHasBeenApplied,
    Event\MonthReport\ExpenseHasBeenApplied
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
        $this->assertCount(0, $report->recordedEvents());
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
        $report->applyIncome($income);
        $this->assertTrue($report->hasIncomeBeenApplied($incomeIdentity));
        $this->assertSame(42, $report->amount()->value());
        $this->assertCount(1, $report->recordedEvents());
        $event = $report->recordedEvents()->last();
        $this->assertInstanceOf(IncomeHasBeenApplied::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($incomeIdentity, $event->income());
        $costIdentity = $this->createMock(FixedCostIdentityInterface::class);
        $costIdentity
            ->method('__toString')
            ->willReturn('foo');
        $cost = new FixedCost(
            $costIdentity,
            'foo',
            new Amount(200),
            new ApplyDay(1),
            $this->createMock(CategoryIdentityInterface::class)
        );
        $this->assertFalse($report->hasFixedCostBeenApplied($costIdentity));
        $this->assertSame($report, $report->applyFixedCost($cost));
        $report->applyFixedCost($cost);
        $this->assertTrue($report->hasFixedCostBeenApplied($costIdentity));
        $this->assertSame(-158, $report->amount()->value());
        $this->assertCount(2, $report->recordedEvents());
        $event = $report->recordedEvents()->last();
        $this->assertInstanceOf(FixedCostHasBeenApplied::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($costIdentity, $event->fixedCost());
        $this->assertSame(
            $report,
            $report->applyExpense(
                $expense = new Expense(
                    $this->createMock(ExpenseIdentityInterface::class),
                    new Amount(4200),
                    $this->createMock(CategoryIdentityInterface::class),
                    new \DateTimeImmutable
                )
            )
        );
        $this->assertSame(-4358, $report->amount()->value());
        $this->assertCount(3, $report->recordedEvents());
        $event = $report->recordedEvents()->last();
        $this->assertInstanceOf(ExpenseHasBeenApplied::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($expense->identity(), $event->expense());
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
        $this->assertSame(642, $report->amount()->value());
    }

    public function testCreate()
    {
        $report = MonthReport::create(
            $identity = $this->createMock(IdentityInterface::class),
            $date = new \DateTimeImmutable('2016-07')
        );

        $this->assertSame($identity, $report->identity());
        $this->assertSame('2016-07', (string) $report);
        $this->assertSame(0, $report->amount()->value());
        $this->assertCount(1, $report->recordedEvents());
        $event = $report->recordedEvents()->first();
        $this->assertInstanceOf(MonthReportWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($date, $event->date());
    }

    /**
     * @expectedException ExpenseManager\Exception\ApplyExpenseOnWrongMonthReportException
     */
    public function testThrowWhenApplyingExpenseOnWrongMonth()
    {
        $report = new MonthReport(
            $this->createMock(IdentityInterface::class),
            new \DateTimeImmutable('2016-07')
        );

        $report->applyExpense(
            new Expense(
                $this->createMock(ExpenseIdentityInterface::class),
                new Amount(4200),
                $this->createMock(CategoryIdentityInterface::class),
                new \DateTimeImmutable('2016-05')
            )
        );
    }

    /**
     * @expectedException ExpenseManager\Exception\ApplyOneOffIncomeOnWrongMonthReportException
     */
    public function testThrowWhenApplyingOneOffIncomeOnWrongMonth()
    {
        $report = new MonthReport(
            $this->createMock(IdentityInterface::class),
            new \DateTimeImmutable('2016-07')
        );

        $report->applyOneOffIncome(
            new OneOffIncome(
                $this->createMock(OneOffIncomeIdentityInterface::class),
                new Amount(5000),
                new \DateTimeImmutable('2016-05')
            )
        );
    }
}
