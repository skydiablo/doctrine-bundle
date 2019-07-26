<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Trait CreatedAtTrait
 * @ORM\HasLifecycleCallbacks
 */
trait CreatedAtTrait {

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Serializer\Groups({"ENTITY_CREATED_AT"})
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function __onPrePersistForCreatedAt() {
        $this->createdAt = $this->createdAt ?? new \DateTime('now');
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

}
