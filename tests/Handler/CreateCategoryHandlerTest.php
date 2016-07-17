<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Handler;

use ExpenseManager\{
    Handler\CreateCategoryHandler,
    Command\CreateCategory,
    Entity\Category,
    Entity\Category\IdentityInterface,
    Repository\CategoryRepositoryInterface
};

class CreateCategoryHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $handler = new CreateCategoryHandler(
            $repo = $this->createMock(CategoryRepositoryInterface::class)
        );
        $called = false;
        $identity = $this->createMock(IdentityInterface::class);
        $repo
            ->method('add')
            ->will($this->returnCallback(function(Category $category) use (&$called, $identity, $repo) {
                $called = true;
                $this->assertSame($identity, $category->identity());
                $this->assertSame('foo', $category->name());
                $this->assertSame('white', (string) $category->color());

                return $repo;
            }));

        $this->assertNull($handler(
            new CreateCategory(
                $identity,
                'foo',
                'white'
            )
        ));
        $this->assertTrue($called);
    }
}
