<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Entity\OneOffIncome\IdentityInterface,
    Amount,
    Event\OneOffIncomeWasCreated,
    Event\OneOffIncome\NoteWasSpecified,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class OneOffIncome implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $amount;
    private $date;
    private $note = '';

    public function __construct(
        IdentityInterface $identity,
        Amount $amount,
        \DateTimeImmutable $date
    ) {
        if ($amount->value() < 0) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->amount = $amount;
        $this->date = $date;
    }

    public static function create(
        IdentityInterface $identity,
        Amount $amount,
        \DateTimeImmutable $date
    ): self {
        $self = new self($identity, $amount, $date);
        $self->record(new OneOffIncomeWasCreated($identity, $amount, $date));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function note(): string
    {
        return $this->note;
    }

    public function specifyNote(string $note): self
    {
        if ($note !== $this->note) {
            $this->note = $note;
            $this->record(new NoteWasSpecified($this->identity, $note));
        }

        return $this;
    }
}
