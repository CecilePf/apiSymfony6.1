<?php

namespace App\Entity\Trait;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function preUpdateEntity(): self
    {
        $this->updatedAt = new DateTime();

        if (method_exists($this, 'preUpdate')) {
            $this->preUpdate();
        }

        return $this;
    }
}