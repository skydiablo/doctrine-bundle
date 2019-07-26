<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Interface EnabledInterface
 */
interface EnabledInterface {

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled);
}
