<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Handler\CreateMonthReportHandler,
    Command\CreateMonthReport,
    Repository\MonthReportRepositoryInterface,
    Entity\MonthReport,
    Entity\MonthReport\IdentityInterface,
    Event\MonthReportWasCreated
};

class CreateMonthReportHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateMonthReportHandler(
            $repo = $this->createMock(MonthReportRepositoryInterface::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(MonthReport $report) use ($identity) {
                return $report->identity() === $identity &&
                    (string) $report === '2016-07' &&
                    $report->recordedEvents()->count() === 1 &&
                    $report->recordedEvents()->first() instanceof MonthReportWasCreated;
            }));

        $this->assertNull($handler(
            new CreateMonthReport(
                $identity,
                '2016-07-01'
            )
        ));
    }
}
