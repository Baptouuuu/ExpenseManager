<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateIncome,
    Handler\CreateIncomeHandler,
    Repository\IncomeRepositoryInterface,
    Entity\Income,
    Entity\Income\IdentityInterface
};

class CreateIncomeHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateIncomeHandler(
            $repo = $this->createMock(IncomeRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->method('add')
            ->will($this->returnCallback(function(Income $income) use (&$called, $identity, $repo) {
                $called = true;
                $this->assertSame($identity, $income->identity());
                $this->assertSame('foo', $income->name());
                $this->assertSame(42, $income->amount()->value());
                $this->assertSame(24, $income->applyDay()->value());
                $this->assertCount(1, $income->recordedEvents());

                return $repo;
            }));

        $this->assertNull($handler(
            new CreateIncome(
                $identity,
                'foo',
                42,
                24
            )
        ));
        $this->assertTrue($called);
    }
}
