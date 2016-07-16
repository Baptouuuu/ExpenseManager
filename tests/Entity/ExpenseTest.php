<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\Expense,
    Entity\Expense\IdentityInterface,
    Entity\Category\IdentityInterface as Category,
    Amount,
    Event\ExpenseWasCreated,
    Event\Expense\NoteWasSpecified
};

class ExpenseTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $expense = new Expense(
            $identity = $this->createMock(IdentityInterface::class),
            $amount = new Amount(4200),
            $category = $this->createMock(Category::class),
            $date = new \DateTimeImmutable
        );

        $this->assertSame($identity, $expense->identity());
        $this->assertSame($amount, $expense->amount());
        $this->assertSame($category, $expense->category());
        $this->assertSame($date, $expense->date());
        $this->assertSame('', $expense->note());
        $this->assertCount(0, $expense->recordedEvents());
    }

    public function testCreate()
    {
        $expense = Expense::create(
            $identity = $this->createMock(IdentityInterface::class),
            $amount = new Amount(4200),
            $category = $this->createMock(Category::class),
            $date = new \DateTimeImmutable
        );

        $this->assertSame($identity, $expense->identity());
        $this->assertSame($amount, $expense->amount());
        $this->assertSame($category, $expense->category());
        $this->assertSame($date, $expense->date());
        $this->assertSame('', $expense->note());
        $this->assertCount(1, $expense->recordedEvents());
        $event = $expense->recordedEvents()->first();
        $this->assertInstanceOf(ExpenseWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($amount, $event->amount());
        $this->assertSame($category, $event->category());
        $this->assertSame($date, $event->date());
    }

    public function testSpecifyComment()
    {
        $expense = new Expense(
            $identity = $this->createMock(IdentityInterface::class),
            new Amount(4200),
            $this->createMock(Category::class),
            new \DateTimeImmutable
        );

        $this->assertSame($expense, $expense->specifyNote('foo'));
        $this->assertSame('foo', $expense->note());
        $this->assertCount(1, $expense->recordedEvents());
        $event = $expense->recordedEvents()->first();
        $this->assertInstanceOf(NoteWasSpecified::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->note());
    }
}
