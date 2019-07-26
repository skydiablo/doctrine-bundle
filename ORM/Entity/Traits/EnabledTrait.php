<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Trait EnabledTrait
 */
trait EnabledTrait {

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean", nullable=false, options={"default": true})
     */
    protected $enabled = true;

    /**
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled) {
        $this->enabled = $enabled;
        return $this;
    }

}
