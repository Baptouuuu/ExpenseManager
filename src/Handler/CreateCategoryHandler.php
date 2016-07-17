<?php
declare(strict_types = 1);

namespace ExpenseManager\Handler;

use ExpenseManager\{
    Command\CreateCategory,
    Repository\CategoryRepositoryInterface,
    Entity\Category,
    Color
};

final class CreateCategoryHandler
{
    private $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateCategory $wished)
    {
        $this->repository->add(
            Category::create(
                $wished->identity(),
                $wished->name(),
                new Color($wished->color())
            )
        );
    }
}
