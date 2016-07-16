<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager;

use ExpenseManager\Color;
use Innmind\Immutable\SetInterface;

class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testChoices()
    {
        $c = Color::choices();

        $this->assertInstanceOf(SetInterface::class, $c);
        $this->assertSame('string', (string) $c->type());
        $this->assertSame(
            ['red', 'yellow', 'green', 'blue', 'magenta', 'cyan', 'gray', 'white'],
            $c->toPrimitive()
        );
        $this->assertSame($c, Color::choices());
    }

    public function testInterface()
    {
        $c = new Color('red');

        $this->assertSame('red', (string) $c);
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenUsingUnknownColor()
    {
        new Color('foo');
    }
}
