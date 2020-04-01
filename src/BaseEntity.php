<?php

namespace Anso\Framework\Base;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use JsonSerializable;

abstract class BaseEntity implements JsonSerializable
{
    /**
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

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

    public function getId(): int
    {
        return $this->id;
    }
}