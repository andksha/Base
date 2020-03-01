<?php

namespace Anso\Framework\Base\Test\DB;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DBTestCase extends TestCase
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    public function assertDBHas(string $classname, array $criteria)
    {
        $this->assertThat([
            'table' => $classname,
            'criteria' => $criteria
        ], new DBHasConstraint($this->em));
    }

    public function assertDBDoesNotHave(string $classname, array $criteria)
    {
        $this->assertThat([
            'table' => $classname,
            'criteria' => $criteria
        ], new DBDoesNotHaveConstraint($this->em));
    }

    public function clearDB(string $table)
    {
        $this->em->createQuery("DELETE FROM $table WHERE 1 = 1")->execute();
    }
}