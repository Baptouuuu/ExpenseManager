<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\SpecifyExpenseNote,
    Repository\ExpenseRepositoryInterface
};

final class SpecifyExpenseNoteHandler
{
    private $repository;

    public function __construct(ExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyExpenseNote $wished)
    {
        $expense = $this->repository->get($wished->identity());

        if ($expense->note() !== $wished->note()) {
            $expense->specifyNote($wished->note());
        }
    }
}
