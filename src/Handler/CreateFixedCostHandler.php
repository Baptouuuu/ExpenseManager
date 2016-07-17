<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateFixedCost,
    Repository\FixedCostRepositoryInterface,
    Entity\FixedCost,
    Amount,
    ApplyDay
};

final class CreateFixedCostHandler
{
    private $repository;

    public function __construct(FixedCostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateFixedCost $wished)
    {
        $this->repository->add(
            FixedCost::create(
                $wished->identity(),
                $wished->name(),
                new Amount($wished->amount()),
                new ApplyDay($wished->applyDay()),
                $wished->category()
            )
        );
    }
}
