<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateOneOffIncome,
    Handler\CreateOneOffIncomeHandler,
    Repository\OneOffIncomeRepositoryInterface,
    Entity\OneOffIncome,
    Entity\OneOffIncome\IdentityInterface
};

class CreateOneOffIncomeHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateOneOffIncomeHandler(
            $repo = $this->createMock(OneOffIncomeRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->method('add')
            ->will($this->returnCallback(function(OneOffIncome $income) use (&$called, $identity, $repo) {
                $called = true;
                $this->assertSame($identity, $income->identity());
                $this->assertSame(42, $income->amount()->value());
                $this->assertSame('160714', $income->date()->format('ymd'));
                $this->assertCount(1, $income->recordedEvents());

                return $repo;
            }));

        $this->assertNull($handler(
            new CreateOneOffIncome(
                $identity,
                42,
                '2016-07-14'
            )
        ));
        $this->assertTrue($called);
    }
}
