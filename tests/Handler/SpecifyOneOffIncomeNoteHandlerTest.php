<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\SpecifyOneOffIncomeNote,
    Handler\SpecifyOneOffIncomeNoteHandler,
    Repository\OneOffIncomeRepositoryInterface,
    Entity\OneOffIncome,
    Entity\OneOffIncome\IdentityInterface,
    Amount
};

class SpecifyOneOffIncomeNoteHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new SpecifyOneOffIncomeNoteHandler(
            $repo = $this->createMock(OneOffIncomeRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($income = new OneOffIncome(
                $this->createMock(IdentityInterface::class),
                new Amount(42),
                new \DateTimeImmutable
            ));

        $this->assertNull($handler(
            $command = new SpecifyOneOffIncomeNote(
                $income->identity(),
                'foo'
            )
        ));
        $this->assertSame('foo', $income->note());
        $this->assertCount(1, $income->recordedEvents());
        $handler($command);
        $this->assertCount(1, $income->recordedEvents());
    }
}
