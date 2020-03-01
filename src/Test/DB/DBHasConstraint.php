<?php

namespace Anso\Framework\Base\Test\DB;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Constraint\Constraint;

class DBHasConstraint extends Constraint
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function matches($other): bool
    {
        $result = $this->em->getRepository($other['table'])->findBy($other['criteria']);

        return empty($result) ? false : true;
    }

    public function toString(): string
    {
        return 'that db has record';
    }
}