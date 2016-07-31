<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Handler\GenerateOldMonthReportHandler,
    Command\GenerateOldMonthReport,
    Repository\MonthReportRepositoryInterface,
    Repository\IncomeRepositoryInterface,
    Repository\FixedCostRepositoryInterface,
    Event\MonthReportWasCreated,
    Event\MonthReport\IncomeHasBeenApplied,
    Event\MonthReport\FixedCostHasBeenApplied,
    Entity\Income,
    Entity\Income\IdentityInterface as IncomeIdentityInterface,
    Amount,
    ApplyDay,
    Entity\FixedCost,
    Entity\FixedCost\IdentityInterface as FixedCostIdentityInterface,
    Entity\Category\IdentityInterface as Category,
    Entity\MonthReport\IdentityInterface
};
use Innmind\Immutable\Set;

class GenerateOldMonthReportHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new GenerateOldMonthReportHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $incomes = $this->createMock(IncomeRepositoryInterface::class),
            $costs = $this->createMock(FixedCostRepositoryInterface::class)
        );
        $repo
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function($report) {
                return (string) $report === '2016-05' &&
                    $report->amount()->value() === -42 &&
                    $report->recordedEvents()->size() === 3 &&
                    $report->recordedEvents()->first() instanceof MonthReportWasCreated &&
                    $report->recordedEvents()->get(1) instanceof IncomeHasBeenApplied &&
                    $report->recordedEvents()->last() instanceof FixedCostHasBeenApplied;
            }));
        $incomes
            ->method('all')
            ->willReturn(
                (new Set(Income::class))->add(
                    new Income(
                        $this->createMock(IncomeIdentityInterface::class),
                        'name',
                        new Amount(42),
                        new ApplyDay(1)
                    )
                )
            );
        $costs
            ->method('all')
            ->willReturn(
                (new Set(FixedCost::class))->add(
                    new FixedCost(
                        $this->createMock(FixedCostIdentityInterface::class),
                        'name',
                        new Amount(84),
                        new ApplyDay(28),
                        $this->createMock(Category::class)
                    )
                )
            );

        $this->assertNull($handler(
            new GenerateOldMonthReport(
                $this->createMock(IdentityInterface::class),
                '2016-05'
            )
        ));
    }

    /**
     * @expectedException ExpenseManager\Exception\MonthReportDateNotAfterThisMonthException
     */
    public function testThrowWhenInvalidDate()
    {
        $handler = new GenerateOldMonthReportHandler(
            $this->createMock(MonthReportRepositoryInterface::class),
            $this->createMock(IncomeRepositoryInterface::class),
            $this->createMock(FixedCostRepositoryInterface::class)
        );
        $handler(
            new GenerateOldMonthReport(
                $this->createMock(IdentityInterface::class),
                (new \DateTime)->modify('+1 month')->format('Y-m')
            )
        );
    }
}
