<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\ApplyFixedCost,
    Handler\ApplyFixedCostHandler,
    Handler\CreateExpenseHandler,
    Repository\FixedCostRepositoryInterface,
    Repository\ExpenseRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\FixedCost,
    Entity\Expense,
    Entity\FixedCost\IdentityInterface,
    Entity\Category\IdentityInterface as CategoryIdentity,
    Entity\Expense\IdentityInterface as ExpenseIdentity,
    Amount,
    ApplyDay
};

class ApplyFixedCostHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $day = (int) (new \DateTime)->format('j');

        if ($day > 28) {
            $this->assertTrue(true, 'Cannot test after the 28th day of the month :(');

            return;
        }

        $handler = new ApplyFixedCostHandler(
            $repo = $this->createMock(FixedCostRepositoryInterface::class),
            new CreateExpenseHandler(
                $expenseRepo = $this->createMock(ExpenseRepositoryInterface::class),
                $categoryRepo = $this->createMock(CategoryRepositoryInterface::class)
            )
        );
        $repo
            ->method('get')
            ->willReturn(new FixedCost(
                $identity = $this->createMock(IdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day),
                $category = $this->createMock(CategoryIdentity::class)
            ));
        $categoryRepo
            ->method('has')
            ->willReturn(true);
        $called = false;
        $expenseIdentity = $this->createMock(ExpenseIdentity::class);
        $expenseRepo
            ->method('add')
            ->will($this->returnCallback(function(Expense $expense) use (&$called, $expenseIdentity, $category, $expenseRepo) {
                $called = true;
                $this->assertSame($expenseIdentity, $expense->identity());
                $this->assertSame(42, $expense->amount()->value());
                $this->assertSame($category, $expense->category());
                $this->assertSame(
                    (new \DateTime('today'))->format('ymd'),
                    $expense->date()->format('ymd')
                );

                return $expenseRepo;
            }));

        $this->assertNull($handler(
            new ApplyFixedCost(
                $identity,
                $expenseIdentity
            )
        ));
        $this->assertTrue($called);
    }

    /**
     * @expectedException ExpenseManager\Exception\CantApplyFixedCostTodayException
     */
    public function testThrowWhenTryingToApplyAFixedCostAnotherDayThanItsSupposedTo()
    {
        $day = (int) (new \DateTime)->format('j') + 1;

        if ($day > 28) {
            $day = 1;
        }

        $handler = new ApplyFixedCostHandler(
            $repo = $this->createMock(FixedCostRepositoryInterface::class),
            new CreateExpenseHandler(
                $this->createMock(ExpenseRepositoryInterface::class),
                $this->createMock(CategoryRepositoryInterface::class)
            )
        );
        $repo
            ->method('get')
            ->willReturn(new FixedCost(
                $identity = $this->createMock(IdentityInterface::class),
                'foo',
                new Amount(42),
                new ApplyDay($day),
                $this->createMock(CategoryIdentity::class)
            ));
        $expenseIdentity = $this->createMock(ExpenseIdentity::class);

        $handler(
            new ApplyFixedCost(
                $identity,
                $expenseIdentity
            )
        );
    }
}
