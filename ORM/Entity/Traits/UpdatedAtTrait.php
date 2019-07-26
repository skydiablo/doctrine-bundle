<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Trait UpdatedAtTrait
 * @ORM\HasLifecycleCallbacks
 */
trait UpdatedAtTrait {

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Serializer\Groups({"ENTITY_UPDATED_AT"})
     */
    protected $updatedAt;

    /**
     * @ORM\PreUpdate()
     */
    public function __onPreUpdateForUpdatedAt() {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist
     */
    public function __onPrePersistForUpdatedAt() {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt ?? new \DateTime();
    }

    /**
     * @return $this
     */
    public function invalidateUpdatedAt() {
        $this->updatedAt = new \DateTime('now');
        return $this;
    }

}
