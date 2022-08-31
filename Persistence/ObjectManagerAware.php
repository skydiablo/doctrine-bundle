<?php
declare(strict_types=1);

namespace SkyDiablo\DoctrineBundle\Persistence;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

interface ObjectManagerAware
{

    public function injectObjectManager(ObjectManager $objectManager, ClassMetadata $classMetadata);

}