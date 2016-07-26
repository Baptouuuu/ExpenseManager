<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyExpense,
    Handler\MonthReport\ApplyExpenseHandler,
    Amount,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Entity\Expense,
    Entity\Expense\IdentityInterface as ExpenseIdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Repository\MonthReportRepositoryInterface,
    Repository\ExpenseRepositoryInterface
};

class ApplyExpenseHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new ApplyExpenseHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $expenses = $this->createMock(ExpenseRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $expenses
            ->method('get')
            ->willReturn($expense = new Expense(
                $this->createMock(ExpenseIdentityInterface::class),
                new Amount(42),
                $this->createMock(CategoryIdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));

        $this->assertNull($handler(
            new ApplyExpense(
                $report->identity(),
                $expense->identity()
            )
        ));
        $this->assertSame(-42, $report->amount()->value());
    }
}
