<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateBudget,
    Handler\CreateBudgetHandler,
    Repository\BudgetRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\Budget,
    Entity\Budget\IdentityInterface,
    Entity\Category\IdentityInterface as Category
};

class CreateBudgetHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateBudgetHandler(
            $repo = $this->createMock(BudgetRepositoryInterface::class),
            $categoryRepo = $this->createMock(CategoryRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $categories = [$this->createMock(Category::class)];
        $repo
            ->method('add')
            ->will($this->returnCallback(function(Budget $budget) use (&$called, $identity, $categories, $repo) {
                $called = true;
                $this->assertSame($identity, $budget->identity());
                $this->assertSame('foo', $budget->name());
                $this->assertSame(4200, $budget->amount()->value());
                $this->assertSame($categories, $budget->categories()->toPrimitive());

                return $repo;
            }));
        $categoryRepo
            ->method('has')
            ->willReturn(true);

        $this->assertNull($handler(
            new CreateBudget(
                $identity,
                'foo',
                4200,
                $categories
            )
        ));
        $this->assertTrue($called);
    }

    /**
     * @expectedException ExpenseManager\Exception\CantUseUnknownCategoryException
     */
    public function testThrowWhenUsingUnknownCategory()
    {
        $handler = new CreateBudgetHandler(
            $this->createMock(BudgetRepositoryInterface::class),
            $repo = $this->createMock(CategoryRepositoryInterface::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->method('has')
            ->willReturn(false);

        $handler(
            new CreateBudget(
                $identity,
                'foo',
                4200,
                [$this->createMock(Category::class)]
            )
        );
    }
}
