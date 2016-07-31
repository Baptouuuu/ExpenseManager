<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Handler\CreateCurrentMonthReportHandler,
    Command\CreateCurrentMonthReport,
    Repository\MonthReportRepositoryInterface,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Event\MonthReportWasCreated
};

class CreateCurrentMonthReportHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateCurrentMonthReportHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(MonthReport $report) use ($identity) {
                return $report->identity() === $identity &&
                    (string) $report === date('Y-m') &&
                    $report->recordedEvents()->count() === 1 &&
                    $report->recordedEvents()->first() instanceof MonthReportWasCreated;
            }));

        $this->assertNull($handler(
            new CreateCurrentMonthReport($identity)
        ));
    }
}
