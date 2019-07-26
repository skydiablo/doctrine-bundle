<?php


namespace SkyDiablo\DoctrineBundle\DBAL\Types;

use Doctrine\DBAL\Types\Type AS BaseType;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeRegisterInterface;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeTrait;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class Type
 */
abstract class Type extends BaseType implements TypeRegisterInterface
{

    use TypeTrait;

    /**
     * override this const with type-name
     */
    const __TYPE_NAME = 'CHANGE_ME';

}