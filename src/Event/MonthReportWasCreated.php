<?php
declare(strict_types = 1);

namespace ExpenseManager\Event;

use ExpenseManager\Entity\MonthReport\IdentityInterface;

final class MonthReportWasCreated
{
    private $identity;
    private $date;

    public function __construct(
        IdentityInterface $identity,
        \DateTimeImmutable $date
    ) {
        $this->identity = $identity;
        $this->date = $date;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
