<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Traits;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Interface UpdatedAtInterface
 */
interface UpdatedAtInterface {

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
