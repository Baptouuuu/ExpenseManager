<?php
declare(strict_types = 1);

namespace Tests\ExpenseManager;

use ExpenseManager\ApplyDay;
use Innmind\Immutable\NumericRange;

class ApplyDayTest extends \PHPUnit_Framework_TestCase
{
    public function testAvailable()
    {
        $this->assertInstanceOf(NumericRange::class, ApplyDay::available());
        $this->assertSame(1.0, ApplyDay::available()->start());
        $this->assertSame(28.0, ApplyDay::available()->end());
        $this->assertSame(1.0, ApplyDay::available()->step());
    }

    public function testInterface()
    {
        $day = new ApplyDay(2);

        $this->assertSame(2, $day->value());
    }

    /**
     * @expectedException ExpenseManager\Exception\InvalidArgumentException
     */
    public function testThrowWhenNotADayAvailable()
    {
        new ApplyDay(30);
    }
}
