<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\SpecifyExpenseNote,
    Handler\SpecifyExpenseNoteHandler,
    Repository\ExpenseRepositoryInterface,
    Entity\Expense,
    Entity\Expense\IdentityInterface,
    Entity\Category\IdentityInterface as Category,
    Amount
};

class SpecifyExpenseNoteHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new SpecifyExpenseNoteHandler(
            $repo = $this->createMock(ExpenseRepositoryInterface::class)
        );
        $repo
            ->method('get')
            ->willReturn($expense = new Expense(
                $this->createMock(IdentityInterface::class),
                new Amount(42),
                $this->createMock(Category::class),
                new \DateTimeImmutable
            ));

        $this->assertNull($handler(
            $command = new SpecifyExpenseNote(
                $expense->identity(),
                'foo'
            )
        ));
        $this->assertSame('foo', $expense->note());
        $this->assertCount(1, $expense->recordedEvents());
        $handler($command);
        $this->assertCount(1, $expense->recordedEvents());
    }
}
