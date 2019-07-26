<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity\Factory;

use Doctrine\Common\Persistence\ObjectManagerAware;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use SkyDiablo\DoctrineBundle\Exception\EntityException;
use SkyDiablo\DoctrineBundle\Factory\ObjectFactory;
use SkyDiablo\DoctrineBundle\ORM\Entity\ActiveEntity;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class ActiveEntityFactory
 */
abstract class ActiveEntityFactory extends ObjectFactory {

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ActiveEntity
     * @throws EntityException
     */
    protected function createObject() {
        $entity = call_user_func_array(array('parent', __FUNCTION__), func_get_args());
        if ($entity instanceof ObjectManagerAware) {
            $this->assignEntityManager($entity);
        } else {
            throw EntityException::notAnActiveEntity($this);
        }
        return $entity;
    }

    /**
     * @param ObjectManagerAware $entity
     *
     * @return ObjectManagerAware
     */
    protected function assignEntityManager(ObjectManagerAware $entity) {
        $classMetadata = $this->entityManager->getClassMetadata(ClassUtils::getClass($entity));
        $entity->injectObjectManager($this->entityManager, $classMetadata);
        return $entity;
    }

}
