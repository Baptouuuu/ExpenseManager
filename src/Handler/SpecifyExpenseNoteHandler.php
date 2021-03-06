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
        $this
            ->repository
            ->get($wished->identity())
            ->specifyNote($wished->note());
    }
}
