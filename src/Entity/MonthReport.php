<?php
declare(strict_types = 1);

namespace ExpenseManager\Entity;

use ExpenseManager\{
    Amount,
    Entity\MonthReport\IdentityInterface,
    Entity\Income\IdentityInterface as IncomeIdentityInterface,
    Entity\FixedCost\IdentityInterface as FixedCostIdentityInterface,
    Event\MonthReportWasCreated,
    Event\MonthReport\IncomeHasBeenApplied,
    Event\MonthReport\FixedCostHasBeenApplied,
    Event\MonthReport\ExpenseHasBeenApplied,
    Event\MonthReport\OneOffIncomeHasBeenApplied,
    Exception\ApplyExpenseOnWrongMonthReportException,
    Exception\ApplyOneOffIncomeOnWrongMonthReportException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};
use Innmind\Immutable\Set;

final class MonthReport implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $date;
    private $amount;
    private $appliedIncomes;
    private $appliedFixedCosts;

    public function __construct(
        IdentityInterface $identity,
        \DateTimeImmutable $date
    ) {
        $this->identity = $identity;
        $this->date = $date;
        $this->amount = new Amount(0);
        $this->appliedIncomes = new Set('string');
        $this->appliedFixedCosts = new Set('string');
    }

    public static function create(
        IdentityInterface $identity,
        \DateTimeImmutable $date
    ): self {
        $self = new self($identity, $date);
        $self->record(new MonthReportWasCreated($identity, $date));

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

    public function applyIncome(Income $income): self
    {
        if (!$this->hasIncomeBeenApplied($income->identity())) {
            $this->appliedIncomes = $this
                ->appliedIncomes
                ->add((string) $income->identity());
            $this->amount = $this->amount->add($income->amount());
            $this->record(new IncomeHasBeenApplied(
                $this->identity,
                $income->identity()
            ));
        }

        return $this;
    }

    public function hasIncomeBeenApplied(IncomeIdentityInterface $identity): bool
    {
        return $this->appliedIncomes->contains((string) $identity);
    }

    public function applyFixedCost(FixedCost $cost): self
    {
        if (!$this->hasFixedCostBeenApplied($cost->identity())) {
            $this->appliedFixedCosts = $this
                ->appliedFixedCosts
                ->add((string) $cost->identity());
            $this->amount = $this->amount->subtract($cost->amount());
            $this->record(new FixedCostHasBeenApplied(
                $this->identity,
                $cost->identity()
            ));
        }

        return $this;
    }

    public function hasFixedCostBeenApplied(FixedCostIdentityInterface $identity): bool
    {
        return $this->appliedFixedCosts->contains((string) $identity);
    }

    public function applyExpense(Expense $expense): self
    {
        if ($expense->date()->format('Y-m') !== (string) $this) {
            throw new ApplyExpenseOnWrongMonthReportException;
        }

        $this->amount = $this->amount->subtract($expense->amount());
        $this->record(new ExpenseHasBeenApplied(
            $this->identity(),
            $expense->identity()
        ));

        return $this;
    }

    public function applyOneOffIncome(OneOffIncome $income): self
    {
        if ($income->date()->format('Y-m') !== (string) $this) {
            throw new ApplyOneOffIncomeOnWrongMonthReportException;
        }

        $this->amount = $this->amount->add($income->amount());
        $this->record(new OneOffIncomeHasBeenApplied(
            $this->identity(),
            $income->identity()
        ));

        return $this;
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m');
    }
}
