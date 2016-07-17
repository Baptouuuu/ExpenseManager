<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Amount,
    Entity\Expense\IdentityInterface,
    Event\ExpenseWasCreated,
    Event\Expense\NoteWasSpecified
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Expense implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $amount;
    private $category;
    private $date;
    private $note = '';

    public function __construct(
        IdentityInterface $identity,
        Amount $amount,
        Category\IdentityInterface $category,
        \DateTimeImmutable $date
    ) {
        $this->identity = $identity;
        $this->amount = $amount;
        $this->category = $category;
        $this->date = $date;
    }

    public static function create(
        IdentityInterface $identity,
        Amount $amount,
        Category\IdentityInterface $category,
        \DateTimeImmutable $date
    ): self {
        $expense = new self($identity, $amount, $category, $date);
        $expense->record(new ExpenseWasCreated(
            $identity,
            $amount,
            $category,
            $date
        ));

        return $expense;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function category(): Category\IdentityInterface
    {
        return $this->category;
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
