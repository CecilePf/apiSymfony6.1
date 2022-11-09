<?php

namespace App\Entity\Trait;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function prePersistEntity(): self
    {
        $this->createdAt = new DateTime();

        if (method_exists($this, 'prePersist')) {
            $this->prePersist();
        }

        return $this;
    }
}