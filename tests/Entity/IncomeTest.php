<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\Income,
    Entity\Income\IdentityInterface,
    Amount,
    ApplyDay,
    Event\IncomeWasCreated
};

class IncomeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $income = new Income(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(42),
            $day = new ApplyDay(24)
        );

        $this->assertSame($identity, $income->identity());
        $this->assertSame('foo', $income->name());
        $this->assertSame($amount, $income->amount());
        $this->assertSame($day, $income->applyDay());
        $this->assertCount(0, $income->recordedEvents());
    }

    public function testCreate()
    {
        $income = Income::create(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(42),
            $day = new ApplyDay(24)
        );

        $this->assertSame($identity, $income->identity());
        $this->assertSame('foo', $income->name());
        $this->assertSame($amount, $income->amount());
        $this->assertSame($day, $income->applyDay());
        $this->assertCount(1, $income->recordedEvents());
        $event = $income->recordedEvents()->first();
        $this->assertInstanceOf(IncomeWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->name());
        $this->assertSame($amount, $event->amount());
        $this->assertSame($day, $event->applyDay());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new Income(
            $this->createMock(IdentityInterface::class),
            '',
            new Amount(42),
            new ApplyDay(24)
        );
    }
}
