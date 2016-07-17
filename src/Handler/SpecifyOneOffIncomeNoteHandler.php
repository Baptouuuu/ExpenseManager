<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\SpecifyOneOffIncomeNote,
    Repository\OneOffIncomeRepositoryInterface
};

final class SpecifyOneOffIncomeNoteHandler
{
    private $repository;

    public function __construct(OneOffIncomeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyOneOffIncomeNote $wished)
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyNote($wished->note());
    }
}
