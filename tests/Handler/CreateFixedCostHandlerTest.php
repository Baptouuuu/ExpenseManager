<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateFixedCost,
    Handler\CreateFixedCostHandler,
    Repository\FixedCostRepositoryInterface,
    Entity\FixedCost,
    Entity\FixedCost\IdentityInterface,
    Entity\Category\IdentityInterface as Category
};

class CreateFixedCostHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateFixedCosthandler(
            $repo = $this->createMock(FixedCostRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $category = $this->createMock(Category::class);
        $repo
            ->method('add')
            ->will($this->returnCallback(function(FixedCost $cost) use (&$called, $identity, $category, $repo) {
                $called = true;
                $this->assertSame($identity, $cost->identity());
                $this->assertSame('foo', $cost->name());
                $this->assertSame(42, $cost->amount()->value());
                $this->assertSame(24, $cost->applyDay()->value());
                $this->assertSame($category, $cost->category());
                $this->assertCount(1, $cost->recordedEvents());

                return $repo;
            }));

        $this->assertNull($handler(
            new CreateFixedCost(
                $identity,
                'foo',
                42,
                24,
                $category
            )
        ));
        $this->assertTrue($called);
    }
}
