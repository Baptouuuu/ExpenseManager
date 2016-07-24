<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\OneOffIncome,
    Entity\OneOffIncome\IdentityInterface,
    Amount,
    Event\OneOffIncomeWasCreated,
    Event\OneOffIncome\NoteWasSpecified
};

class OneOffIncomeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $income = new OneOffIncome(
            $identity = $this->createMock(IdentityInterface::class),
            $amount = new Amount(42),
            $date = new \DateTimeImmutable
        );

        $this->assertSame($identity, $income->identity());
        $this->assertSame($amount, $income->amount());
        $this->assertSame($date, $income->date());
        $this->assertSame('', $income->note());
        $this->assertCount(0, $income->recordedEvents());
    }

    public function testCreate()
    {
        $income = OneOffIncome::create(
            $identity = $this->createMock(IdentityInterface::class),
            $amount = new Amount(42),
            $date = new \DateTimeImmutable
        );

        $this->assertSame($identity, $income->identity());
        $this->assertSame($amount, $income->amount());
        $this->assertSame($date, $income->date());
        $this->assertSame('', $income->note());
        $this->assertCount(1, $income->recordedEvents());
        $event = $income->recordedEvents()->first();
        $this->assertInstanceOf(OneOffIncomeWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame($amount, $event->amount());
        $this->assertSame($date, $event->date());
    }

    public function testSpecifyNote()
    {
        $income = new OneOffIncome(
            $this->createMock(IdentityInterface::class),
            new Amount(42),
            new \DateTimeImmutable
        );

        $this->assertSame($income, $income->specifyNote('foo'));
        $this->assertSame('foo', $income->note());
        $this->assertCount(1, $income->recordedEvents());
        $event = $income->recordedEvents()->first();
        $this->assertInstanceOf(NoteWasSpecified::class, $event);
        $this->assertSame($income->identity(), $event->identity());
        $this->assertSame('foo', $event->note());
        $income->specifyNote('foo');
        $this->assertCount(1, $income->recordedEvents());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenAmountIsNegative()
    {
        new OneOffIncome(
            $this->createMock(IdentityInterface::class),
            new Amount(-42),
            new \DateTimeImmutable
        );
    }
}
