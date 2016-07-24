<?php
declare(strict_types = 1);

namespace ExpenseManager\Repository;

use ExpenseManager\Entity\{
    MonthReport,
    MonthReport\IdentityInterface
};
use Innmind\Specification\SpecificationInterface;
use Innmind\Immutable\SetInterface;

interface MonthReportRepositoryInterface
{
    public function add(MonthReport $report): self;
    public function get(IdentityInterface $identity): MonthReport;
    public function has(IdentityInterface $identity): bool;
    public function remove(IdentityInterface $identity): self;

    /**
     * @return SetInterface<MonthReport>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<MonthReport>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
