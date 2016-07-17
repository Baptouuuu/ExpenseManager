<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\FixedCost,
    Entity\FixedCost\IdentityInterface,
    Entity\Category\IdentityInterface as Category,
    Amount,
    ApplyDay,
    Event\FixedCostWasCreated
};

class FixedCostTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $cost = new FixedCost(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(42),
            $day = new ApplyDay(24),
            $category = $this->createMock(Category::class)
        );

        $this->assertSame($identity, $cost->identity());
        $this->assertSame('foo', $cost->name());
        $this->assertSame($amount, $cost->amount());
        $this->assertSame($day, $cost->applyDay());
        $this->assertSame($category, $cost->category());
        $this->assertCount(0, $cost->recordedEvents());
    }

    public function testCreate()
    {
        $cost = FixedCost::create(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(42),
            $day = new ApplyDay(24),
            $category = $this->createMock(Category::class)
        );

        $this->assertSame($identity, $cost->identity());
        $this->assertSame('foo', $cost->name());
        $this->assertSame($amount, $cost->amount());
        $this->assertSame($day, $cost->applyDay());
        $this->assertSame($category, $cost->category());
        $this->assertCount(1, $cost->recordedEvents());
        $event = $cost->recordedEvents()->first();
        $this->assertInstanceOf(FixedCostWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->name());
        $this->assertSame($amount, $event->amount());
        $this->assertSame($day, $event->applyDay());
        $this->assertSame($category, $event->category());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new FixedCost(
            $this->createMock(IdentityInterface::class),
            '',
            new Amount(42),
            new ApplyDay(24),
            $this->createMock(Category::class)
        );
    }
}
