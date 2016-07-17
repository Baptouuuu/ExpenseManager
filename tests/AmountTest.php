<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager;

use ExpenseManager\Amount;

class AmountTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $a = new Amount(42);

        $this->assertSame(42, $a->value());

        try {
            new Amount(42.1);
            $this->fail('it should throw a type error');
        } catch (\TypeError $e) {
            $this->assertTrue(true);
        }
    }

    public function testAdd()
    {
        $a = new Amount(42);

        $a3 = $a->add($a2 = new Amount(24));

        $this->assertInstanceOf(Amount::class, $a3);
        $this->assertNotSame($a3, $a);
        $this->assertNotSame($a3, $a2);
        $this->assertSame(42, $a->value());
        $this->assertSame(24, $a2->value());
        $this->assertSame(66, $a3->value());
    }

    public function testSubtract()
    {
        $a = new Amount(42);

        $a3 = $a->subtract($a2 = new Amount(24));

        $this->assertInstanceOf(Amount::class, $a3);
        $this->assertNotSame($a3, $a);
        $this->assertNotSame($a3, $a2);
        $this->assertSame(42, $a->value());
        $this->assertSame(24, $a2->value());
        $this->assertSame(18, $a3->value());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenAmountIsNegative()
    {
        new Amount(-42);
    }
}
