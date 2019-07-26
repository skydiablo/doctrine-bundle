<?php


namespace SkyDiablo\DoctrineBundle\DBAL\Types;

use SkyDiablo\DoctrineBundle\DBAL\Types\AbstractEnumType;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeRegisterInterface;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeTrait;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class EnumType
 */
abstract class EnumType extends AbstractEnumType implements TypeRegisterInterface
{

    use TypeTrait;

    /**
     * override this const with type-name
     */
    const __TYPE_NAME = 'CHANGE_ME';


}