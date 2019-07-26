<?php

namespace SkyDiablo\DoctrineBundle\Exception;

use Doctrine\Common\Util\ClassUtils;
use SkyDiablo\DoctrineBundle\ORM\Entity\ActiveEntity;
use SkyDiablo\DoctrineBundle\ORM\Entity\EntityInterface;
use SkyDiablo\DoctrineBundle\ORM\Entity\Factory\ActiveEntityFactory;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class EntityException
 */
class EntityException extends \Exception {

    const CODE_OBJECT_MANAGER_MISSING = 100;
    const CODE_REPERSIST = 101;
    const CODE_NOT_AN_ACTIVE_ENTITY = 102;

    public static function objectManagerMissing() {
        return new self('ObjectManager missing', self::CODE_OBJECT_MANAGER_MISSING);
    }

    public static function rePersist(EntityInterface $entity) {
        return new self(sprintf('Trying to re-persist entity: %s [ID: %u]', ClassUtils::getClass($entity), $entity->getId()), self::CODE_REPERSIST);
    }

    public static function notAnActiveEntity(ActiveEntityFactory $activeEntityFactory) {
        return new self(sprintf('Created entity object of factory "%s" is not an "%s" object', get_class($activeEntityFactory), ActiveEntity::class));
    }

}
