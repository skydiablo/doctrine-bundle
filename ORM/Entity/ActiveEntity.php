<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class ActiveDBEntity
 */
abstract class ActiveEntity extends Entity implements ActiveEntityInterface {

    use ActiveEntityTrait;
}
