<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler\MonthReport;

use ExpenseManager\{
    Command\MonthReport\ApplyFixedCost,
    Handler\MonthReport\ApplyFixedCostHandler,
    Amount,
    ApplyDay,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Entity\FixedCost,
    Entity\FixedCost\IdentityInterface as FixedCostIdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentityInterface,
    Repository\MonthReportRepositoryInterface,
    Repository\FixedCostRepositoryInterface
};

class ApplyFixedCostHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $day = (int) (new \DateTime)->format('j');

        if ($day > 28) {
            $this->assertTrue(true, 'Cannot test after the 28th day of the month :(');

            return;
        }

        $handler = new ApplyFixedCostHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $fixedCosts = $this->createMock(FixedCostRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $fixedCosts
            ->method('get')
            ->willReturn($fixedCost = new FixedCost(
                $this->createMock(FixedCostIdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day),
                $this->createMock(CategoryIdentityInterface::class)
            ));

        $this->assertNull($handler(
            new ApplyFixedCost(
                $report->identity(),
                $fixedCost->identity()
            )
        ));
        $this->assertTrue($report->hasFixedCostBeenApplied($fixedCost->identity()));
    }

    /**
     * @expectedException ExpenseManager\Exception\CantApplyFixedCostTodayException
     */
    public function testThrowWhenTryingToApplyFixedCostAnotherDayItIsSupposedTo()
    {
        $day = (int) (new \DateTime)->format('j') + 1;

        if ($day > 28) {
            $day = 1;
        }

        $handler = new ApplyFixedCostHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class),
            $fixedCosts = $this->createMock(FixedCostRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($report = new MonthReport(
                $this->createMock(IdentityInterface::class),
                new \DateTimeImmutable('2016-07')
            ));
        $fixedCosts
            ->method('get')
            ->willReturn($fixedCost = new FixedCost(
                $this->createMock(FixedCostIdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day),
                $this->createMock(CategoryIdentityInterface::class)
            ));

        $handler(
            new ApplyFixedCost(
                $report->identity(),
                $fixedCost->identity()
            )
        );
    }
}
