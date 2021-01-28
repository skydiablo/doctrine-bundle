<?php

declare(strict_types=1);

namespace SkyDiablo\DoctrineBundle\ORM\Entity;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use SkyDiablo\DoctrineBundle\Exception\EntityException;
use JMS\Serializer\Annotation as Serializer;

/**
 * Description of ActiveEntityTrait
 *
 * @author SkyDiablo <skydiablo@gmx.net>
 */
trait ActiveEntityTrait {

    /**
     * @var ObjectManager
     * @Serializer\Exclude()
     */
    protected $_objectManager;

    /**
     * @var ClassMetadata
     * @Serializer\Exclude()
     */
    protected $_classMetaData;

    public function injectObjectManager(ObjectManager $objectManager, ClassMetadata $classMetadata) {
        $this->_objectManager = $objectManager;
        $this->_classMetaData = $classMetadata;
    }

    /**
     * @return ObjectManager
     * @throws EntityException
     */
    protected function getObjectManager() {
        if ($this->_objectManager instanceof ObjectManager) {
            return $this->_objectManager;
        } else {
            throw EntityException::objectManagerMissing();
        }
    }

    /**
     * @see persist
     * @see flush
     * @return $this
     */
    public function save() {
        $this->persist();
        $this->flush();
        return $this;
    }

    /**
     * @see remove
     * @see flush
     * @return $this
     */
    public function delete() {
        $this->remove();
        $this->flush();
        return $this;
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     * @return $this
     */
    public function flush() {
        $this->getObjectManager()->flush($this);
        return $this;
    }

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     * @return $this
     */
    public function persist() {
        $this->getObjectManager()->persist($this);
        return $this;
    }

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the flush operation.
     * @return $this
     */
    public function remove() {
        $this->getObjectManager()->remove($this);
        return $this;
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     * @return $this
     */
    public function refresh() {
        $this->getObjectManager()->refresh($this);
        return $this;
    }

    /**
     * Merges the state of a detached object into the persistence context
     * of this ObjectManager and returns the managed copy of the object.
     * The object passed to merge will not become associated/managed with this ObjectManager.
     * @return $this
     */
    public function merge() {
        $this->getObjectManager()->merge($this);
        return $this;
    }

    /**
     * Detaches an object from the ObjectManager, causing a managed object to
     * become detached. Unflushed changes made to the object if any
     * (including removal of the object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     * @return $this
     */
    public function detach() {
        $this->getObjectManager()->detach($this);
        return $this;
    }

}
