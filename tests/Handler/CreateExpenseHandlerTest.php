<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateExpense,
    Handler\CreateExpenseHandler,
    Repository\ExpenseRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\Expense,
    Entity\Expense\IdentityInterface,
    Entity\Category\IdentityInterface as Category
};

class CreateExpenseHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateExpenseHandler(
            $repo = $this->createMock(ExpenseRepositoryInterface::class),
            $categoryRepo = $this->createMock(CategoryRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $category = $this->createMock(Category::class);
        $repo
            ->method('add')
            ->will($this->returnCallback(function(Expense $expense) use (&$called, $identity, $category, $repo) {
                $called = true;
                $this->assertSame($identity, $expense->identity());
                $this->assertSame(42, $expense->amount()->value());
                $this->assertSame($category, $expense->category());
                $this->assertSame('160714', $expense->date()->format('ymd'));

                return $repo;
            }));
        $categoryRepo
            ->method('has')
            ->willReturn(true);

        $this->assertNull($handler(
            new CreateExpense(
                $identity,
                42,
                $category,
                '2016-07-14'
            )
        ));
        $this->assertTrue($called);
    }

    /**
     * @expectedException ExpenseManager\Exception\CantUseUnknownCategoryException
     */
    public function testThrowWhenUsingUnknownCategory()
    {
        $handler = new CreateExpenseHandler(
            $this->createMock(ExpenseRepositoryInterface::class),
            $categoryRepo = $this->createMock(CategoryRepositoryInterface::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $category = $this->createMock(Category::class);
        $categoryRepo
            ->method('has')
            ->willReturn(false);

        $handler(
            new CreateExpense(
                $identity,
                42,
                $category,
                '2016-07-14'
            )
        );
    }
}
