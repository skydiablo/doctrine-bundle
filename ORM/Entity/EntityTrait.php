<?php

namespace SkyDiablo\DoctrineBundle\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait EntityTrait
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Type("integer")
     * @Serializer\Groups({"ENTITY_ID"})
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * Check for equal objects by ID
     * @param EntityInterface $entity
     * @return bool
     */
    public function equal(EntityInterface $entity)
    {
        return $entity->getId() === $this->getId();
    }

    /**
     * reset id to define an new entity
     */
    public function __clone()
    {
        $this->id = null;
    }

    public function getShortClassName(bool $lowercase = false)
    {
        $shortName = (new \ReflectionClass(get_called_class()))->getShortName();
        return $lowercase ? strtolower($shortName) : $shortName;
    }

}
