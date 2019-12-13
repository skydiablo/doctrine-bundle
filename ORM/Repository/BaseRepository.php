<?php

namespace SkyDiablo\DoctrineBundle\ORM\Repository;

use Closure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use SkyDiablo\DoctrineBundle\Exception\EntityException;
use SkyDiablo\DoctrineBundle\ORM\Entity\Entity;
use SkyDiablo\DoctrineBundle\ORM\Entity\EntityInterface;

/**
 * Created by Created by SkyDiablo
 * Class BaseRepository
 * @package SkyDiablo\DoctrineBundle\ORM\Repository
 * @method EntityInterface|null find($id, $lockMode = null, $lockVersion = null)
 */
abstract class BaseRepository extends ServiceEntityRepository
{

    const ENTITY = 'entity';
    const ORDER_BY_ASC = 'ASC';
    const ORDER_BY_DESC = 'DESC';

    private $debugMode = false;

    /**
     * @return bool
     */
    public function getDebugMode()
    {
        return (bool)$this->debugMode;
    }

    /**
     * @param $debugMode
     * @return $this
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = (bool)$debugMode;
        return $this;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->createQueryBuilder()->getQuery();
    }

    /**
     * @param string $alias
     * @param string|null $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias = self::ENTITY, $indexBy = null)
    {
        return parent::createQueryBuilder($alias, $indexBy);
    }

    /**
     * @param EntityInterface $entity
     * @return $this
     * @throws EntityException
     */
    public function persist(EntityInterface $entity)
    {
        if ($this->debugMode && $entity->getId()) {
            if (!$this->getEntityManager()->contains($entity)) {
                throw EntityException::rePersist($entity);
            }
        }
        $this->getEntityManager()->persist($entity);
        return $this;
    }

    /**
     * @param EntityInterface $entity
     * @return $this
     */
    public function remove(EntityInterface $entity)
    {
        $this->getEntityManager()->remove($entity);
        return $this;
    }

    /**
     * @param EntityInterface|null $entity
     * @return $this
     */
    public function flush(EntityInterface $entity = null)
    {
        $this->getEntityManager()->flush($entity);
        return $this;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity)
    {
        $this->persist($entity)->flush($entity);
        return $entity;
    }

    /**
     * @param int[] $ids
     * @return int number of effected elements
     */
    public function deleteByIds(array $ids)
    {
        $counter = 0;
        while ($package = array_splice($ids, 0, 10)) { // batch in sub packages
            $entities = $this->getByIds($package); // load entities - to benefit of all the ORM features!
            $counter += count($entities);
            array_map(function (EntityInterface $entity) {
                $this->remove($entity); // flag to remove
            }, $entities);
            $this->flush(); // FLSUH !!!
        }
        return $counter;
    }

    /**
     * @param EntityInterface $entity
     * @return $this
     */
    public function delete(EntityInterface $entity)
    {
        return $this->remove($entity)->flush($entity);
    }

    /**
     * @param EntityInterface $entity
     * @return $this
     */
    public function detach(EntityInterface $entity)
    {
        $this->getEntityManager()->detach($entity);
        return $this;
    }

    /**
     * Get Entity by "id"
     * Alias for "find"
     * @param int $id
     * @return EntityInterface
     * @see find
     */
    public function getById($id)
    {
        return $this->find((int)$id);
    }

    /**
     * return an array of requested objects by id. array key ist object id
     * @param int[] $ids
     * @param bool $indexById
     * @return EntityInterface[]
     */
    public function getByIds(array $ids, bool $indexById = false)
    {
        $qb = $this->createQueryBuilder(self::ENTITY, $indexById ? $this->entityField('id') : null); // INDEX BY entity.id
        return $qb
            ->where(
                $qb->expr()->in($this->entityField('id'), ':ids')
            )
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->getQuery()->execute();
    }

    /**
     * @param int $amount
     * @param int $offset
     * @param null $order ASC|DESC check class const
     * @param string $orderField Entity field. BEWARE: there is no injection protection!
     * @return EntityInterface[]
     */
    public function getAll($amount = null, $offset = null, $order = null, $orderField = 'id')
    {
        return $this->getAllQueryBuilder($amount, $offset, $order, $orderField)->getQuery()->execute();
    }

    /**
     * @param int $amount
     * @param int $offset
     * @param string $order ASC|DESC check class const
     * @param string $orderField Entity field. BEWARE: there is no injection protection!
     * @return QueryBuilder
     */
    protected function getAllQueryBuilder($amount = null, $offset = null, $order = null, $orderField = 'id')
    {
        $qb = $this->createQueryBuilder();
        if ($order) {
            switch (strtoupper($order)) {
                case self::ORDER_BY_ASC:
                    $qb->orderBy($qb->expr()->asc($this->entityField($orderField)));
                    break;
                case self::ORDER_BY_DESC:
                    $qb->orderBy($qb->expr()->desc($this->entityField($orderField)));
                    break;
            }
        }
        return $qb
            ->setMaxResults((int)$amount ?: null)
            ->setFirstResult((int)$offset ?: null);
    }

    /**
     * @param string $field
     * @param Entity $entity
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    protected function getByRelatedEntityQueryBuilder(string $field, Entity $entity, QueryBuilder $queryBuilder = null)
    {
        return $this->getByFieldQueryBuilder($field, $entity, null, $queryBuilder);
    }

    protected function getByFieldQueryBuilder(string $field, $value, $type = null, QueryBuilder $queryBuilder = null)
    {
        $qb = $queryBuilder ?: $this->createQueryBuilder();
        return $qb
            ->andWhere(
                $qb->expr()->eq(
                    $this->entityField($field), ':' . $field
                )
            )
            ->setParameter($field, $value, $type);
    }

    /**
     * @param string $field
     * @param Entity $entity
     * @return Entity
     */
    protected function getOneOrNullByRelatedEntity(string $field, Entity $entity)
    {
        return $this->getByRelatedEntityQueryBuilder($field, $entity)->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $field
     * @param Entity $entity
     * @param int|null $amount
     * @param int|null $offset
     * @param string|null $order
     * @param string $orderField
     * @return QueryBuilder
     */
    protected function getAllByRelatedEntityQueryBuilder(string $field, Entity $entity, $amount = null, $offset = null, $order = null, $orderField = 'id')
    {
        $qb = $this->getAllQueryBuilder($amount, $offset, $order, $orderField);
        return $this->getByRelatedEntityQueryBuilder($field, $entity, $qb);
    }

    /**
     * @param string $field
     * @param Entity $entity
     * @param null $amount
     * @param null $offset
     * @param null $order
     * @param string $orderField
     * @return Entity[]
     */
    protected function getAllByRelatedEntity(string $field, Entity $entity, $amount = null, $offset = null, $order = null, $orderField = 'id')
    {
        return $this->getAllByRelatedEntityQueryBuilder($field, $entity, $amount, $offset, $order, $orderField)->getQuery()->execute();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    /**
     * Truncate Table -- disable the FOREIGN_KEY_CHECKS
     * @return boolean
     */
    public function truncate()
    {
        $classMetadata = $this->getClassMetadata();
        $connection = $this->getEntityManager()->getConnection();
        $connection->beginTransaction();
        try {
            $q = $connection->getDatabasePlatform()->getTruncateTableSQL(
                $classMetadata->getTableName()
            );
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollBack();
            return false;
        }
    }

    /**
     * Delete ALL entitys from table -- disable the FOREIGN_KEY_CHECKS
     * @return boolean
     */
    public function deleteAll()
    {
        $classMetadata = $this->getClassMetadata();
        $connection = $this->getEntityManager()->getConnection();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('DELETE FROM ' . $classMetadata->getTableName());
            // Beware of ALTER TABLE here -- it's another DDL statement
            // and will cause an implicit commit.
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollBack();
            return false;
        }
    }

    /**
     * Set the Auto-increment value
     * @param integer $index
     * @return boolean
     */
    public function setAutoIncrement($index)
    {
        $classMetadata = $this->getClassMetadata();
        $connection = $this->getEntityManager()->getConnection();
        $connection->beginTransaction();
        try {
            $params = [
                'index' => $index
            ];
            $types = [
                'index' => Type::INTEGER
            ];
            $connection->executeQuery(
                "ALTER TABLE {$classMetadata->getTableName()} AUTO_INCREMENT = :index", $params, $types
            );
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollBack();
            return false;
        }
    }

    /**
     * @param string $field
     * @param string $entityName
     *
     * @return string
     */
    public function entityField($field, $entityName = self::ENTITY)
    {
        return sprintf('%s.%s', $entityName, $field);
    }

    /**
     * @param string $className
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public static function discriminatorByClass($className)
    {
        if (!is_string($className)) {
            throw new InvalidArgumentException('Argument "classname" should be a string, given: ' . gettype($className));
        }

        $parts = explode('\\', $className);
        return strtolower(end($parts));
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public static function discriminatorByEntity(EntityInterface $entity)
    {
        return self::discriminatorByClass(ClassUtils::getClass($entity));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Closure $closure
     * @param int $batchSize
     * @param bool $autoFlush
     * @param null $indexBy
     * @return bool
     * @see https://packagist.org/packages/ocramius/doctrine-batch-utils
     */
    public function pageThrough(QueryBuilder $queryBuilder, Closure $closure, int $batchSize = 100, $autoFlush = false, $indexBy = null)
    {
        if (!$indexBy) {
            $indexBy = $this->entityField('id');
        }
        $queryBuilder
            ->addOrderBy($this->entityField('id'), self::ORDER_BY_ASC)
            ->andWhere(
                $queryBuilder->expr()->gt($this->entityField('id'), ':__id__')
            )
            ->setMaxResults($batchSize)
            ->indexBy(self::ENTITY, $indexBy);

        $lastId = 0;
        while ($elements = $queryBuilder->setParameter('__id__', $lastId, Type::INTEGER)->getQuery()->execute()) {
            $elements = new ArrayCollection($elements);
            $lastElement = $elements->last();
            $lastId = $lastElement->getId();
            $nextBatch = $elements->forAll($closure);
            if ($autoFlush) {
                $this->flush();
            }
            if (!$nextBatch) {
                break;
            }
            $this->clear(); //prevent memory leak
        }
        return true;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Closure $closure (Takes an array of result objects as first parameter)
     * @param int $batchSize
     * @param bool $autoFlush
     * @param null $indexBy
     * @return bool
     */
    public function pageThroughBatch(QueryBuilder $queryBuilder = null, Closure $closure, int $batchSize = 100, $autoFlush = false, $indexBy = null)
    {
        if (!$queryBuilder) {
            $queryBuilder = $this->getAllQueryBuilder();
        }
        if (!$indexBy) {
            $indexBy = $this->entityField('id');
        }
        $queryBuilder
            ->addOrderBy($this->entityField('id'), self::ORDER_BY_ASC)
            ->andWhere(
                $queryBuilder->expr()->gt($this->entityField('id'), ':__id__')
            )
            ->setMaxResults($batchSize)
            ->indexBy(self::ENTITY, $indexBy);

        $lastId = 0;
        while ($elements = $queryBuilder->setParameter('__id__', $lastId, Type::INTEGER)->getQuery()->execute()) {
            $collection = new ArrayCollection($elements);
            $lastElement = $collection->last();
            $lastId = $lastElement->getId();
            $nextBatch = $closure($elements);
            if ($autoFlush) {
                $this->flush();
            }
            if (!$nextBatch) {
                break;
            }
            $this->clear(); //prevent memory leak
        }
        return true;
    }

    /**
     * @param Closure $closure
     * @param int $batchSize
     * @param bool $autoFlush
     * @param null $indexBy
     * @return bool
     */
    public function pageThroughAll(Closure $closure, int $batchSize = 100, $autoFlush = false, $indexBy = null)
    {
        return $this->pageThroughBatch(
            null,
            $closure,
            $batchSize,
            $autoFlush,
            $indexBy
        );
    }

    /**
     * @return $this
     */
    public function disableSoftDeleteFilter()
    {
        $filters = $this->getEntityManager()->getFilters();
        $filters->disable('softdeleteable');
        return $this;
    }

    /**
     * @return $this
     */
    public function enableSoftDeleteFilter()
    {
        $filters = $this->getEntityManager()->getFilters();
        $filters->enable('softdeleteable');
        return $this;
    }

    /**
     * @return bool
     */
    public function isSoftDeleteFilterEnabled()
    {
        $filters = $this->getEntityManager()->getFilters();
        return $filters->isEnabled('softdeleteable');
    }

    /**
     * @param Closure $call
     * @return mixed
     */
    protected function runInDisabledSoftDeletableFilter(Closure $call)
    {
        if ($oldState = $this->isSoftDeleteFilterEnabled()) {
            $this->disableSoftDeleteFilter();
        }
        try {
            return $call();
        } finally {
            if ($oldState) {
                $this->enableSoftDeleteFilter();
            }
        }
    }

}
