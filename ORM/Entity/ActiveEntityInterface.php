<?php

declare(strict_types=1);

namespace SkyDiablo\DoctrineBundle\ORM\Entity;

use Doctrine\Common\Persistence\ObjectManagerAware;

/**
 * Description of ActiveEntityInterface
 *
 * @author SkyDiablo <skydiablo@gmx.net>
 */
interface ActiveEntityInterface extends ObjectManagerAware {
    
    /**
     * @see persist
     * @see flush
     * @return $this
     */
    public function save();
    
    /**
     * @see remove
     * @see flush
     * @return $this
     */
    public function delete();

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     * @return $this
     */
    public function flush();

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     * @return $this
     */
    public function persist();

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the flush operation.
     * @return $this
     */
    public function remove();

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     * @return $this
     */
    public function refresh();

    /**
     * Merges the state of a detached object into the persistence context
     * of this ObjectManager and returns the managed copy of the object.
     * The object passed to merge will not become associated/managed with this ObjectManager.
     * @return $this
     */
    public function merge();

    /**
     * Detaches an object from the ObjectManager, causing a managed object to
     * become detached. Unflushed changes made to the object if any
     * (including removal of the object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     * @return $this
     */
    public function detach();
    
}
