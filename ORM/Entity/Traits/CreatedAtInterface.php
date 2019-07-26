<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Interface CreatedAtInterface
 */
interface CreatedAtInterface {

    /**
     * @return \DateTime
     */
    public function getCreatedAt();
}
