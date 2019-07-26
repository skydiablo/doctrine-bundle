<?php


namespace SkyDiablo\DoctrineBundle\DBAL\Types\Common;

use SkyDiablo\DoctrineBundle\DBAL\Types\Type;
use SkyDiablo\DoctrineBundle\Model\Common\Version;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class VersionType
 */
class VersionType extends Type
{

    const __TYPE_NAME = 'version';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'INT(10)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_null($value)) {
            return Version::parse(long2ip($value), Version::DEFAULT_FORMAT);
        }
        return null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Version) {
            return $value->toLong();
        }
        return null;
    }

    public function getBindingType()
    {
        return \PDO::PARAM_INT;
    }

}
