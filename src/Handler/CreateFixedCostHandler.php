<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateFixedCost,
    Repository\FixedCostRepositoryInterface,
    Repository\CategoryRepositoryInterface,
    Entity\FixedCost,
    Amount,
    ApplyDay,
    Exception\CantUseUnknownCategoryException
};

final class CreateFixedCostHandler
{
    private $repository;
    private $categories;

    public function __construct(
        FixedCostRepositoryInterface $repository,
        CategoryRepositoryInterface $categories
    ) {
        $this->repository = $repository;
        $this->categories = $categories;
    }

    public function __invoke(CreateFixedCost $wished)
    {
        if (!$this->categories->has($wished->category())) {
            throw new CantUseUnknownCategoryException;
        }

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
