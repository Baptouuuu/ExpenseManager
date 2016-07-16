<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager\Entity;

use ExpenseManager\{
    Entity\Category,
    Entity\Category\IdentityInterface,
    Color,
    Event\CategoryWasCreated
};

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $category = new Category(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $color = new Color('white')
        );

        $this->assertSame($identity, $category->identity());
        $this->assertSame('foo', $category->name());
        $this->assertSame($color, $category->color());
        $this->assertCount(0, $category->recordedEvents());
    }

    public function testCreate()
    {
        $category = Category::create(
            $identity = $this->createMock(IdentityInterface::class),
            'foo',
            $color = new Color('white')
        );

        $this->assertSame($identity, $category->identity());
        $this->assertSame('foo', $category->name());
        $this->assertSame($color, $category->color());
        $this->assertCount(1, $category->recordedEvents());
        $event = $category->recordedEvents()->first();
        $this->assertInstanceOf(CategoryWasCreated::class, $event);
        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->name());
        $this->assertSame($color, $event->color());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new Category(
            $this->createMock(IdentityInterface::class),
            '',
            new Color('white')
        );
    }
}
