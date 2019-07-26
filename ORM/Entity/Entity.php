<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class DBEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class Entity implements EntityInterface {

    use EntityTrait;

}
