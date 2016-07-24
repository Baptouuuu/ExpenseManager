<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\Budget,
    Entity\Budget\IdentityInterface,
    Entity\Category\IdentityInterface as Category,
    Event\BudgetWasCreated,
    Amount
};
use Innmind\Immutable\Set;

class BudgetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $budget = new Budget(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(100),
            $categories = (new Set(Category::class))->add(
                $this->createMock(Category::class)
            )
        );

        $this->assertSame($identity, $budget->identity());
        $this->assertSame('foo', $budget->name());
        $this->assertSame($amount, $budget->amount());
        $this->assertSame($categories, $budget->categories());
        $this->assertCount(0, $budget->recordedEvents());
    }

    public function testCreate()
    {
        $budget = Budget::create(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $amount = new Amount(100),
            $categories = (new Set(Category::class))->add(
                $this->createMock(Category::class)
            )
        );

        $this->assertSame($identity, $budget->identity());
        $this->assertSame('foo', $budget->name());
        $this->assertSame($amount, $budget->amount());
        $this->assertSame($categories, $budget->categories());
        $this->assertCount(1, $budget->recordedEvents());
        $event = $budget->recordedEvents()->first();
        $this->assertInstanceOf(BudgetWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->name());
        $this->assertSame($amount, $event->amount());
        $this->assertSame($categories, $event->categories());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenAmountIsNegative()
    {
        new Budget(
            $this->createMock(IdentityInterface::class),
            'foo',
            new Amount(-100),
            (new Set(Category::class))->add(
                $this->createMock(Category::class)
            )
        );
    }
}
