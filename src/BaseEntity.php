<?php

namespace Anso\Framework\Base;

use DateTime;
use Doctrine\ORM\Mapping\Column;

abstract class BaseEntity
{
    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $created_at;

    public function __construct()
    {
        $this->setCreatedAt();
    }

    protected function setCreatedAt()
    {
        if (!$this->created_at) {
            $this->created_at = new DateTime('now');
        }
    }

    public function getCreatedAt()
    {
        return $this->created_at->format('H:i:s Y-m-d');
    }
}