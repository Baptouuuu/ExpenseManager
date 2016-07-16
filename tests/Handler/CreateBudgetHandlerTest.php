<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateBudget,
    Handler\CreateBudgetHandler,
    Repository\BudgetRepositoryInterface,
    Entity\Budget,
    Entity\Budget\IdentityInterface,
    Entity\Category\IdentityInterface as Category
};

class CreateBudgetHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateBudgetHandler(
            $repo = $this->createMock(BudgetRepositoryInterface::class)
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
}
