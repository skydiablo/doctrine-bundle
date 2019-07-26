<?php


namespace SkyDiablo\DoctrineBundle\DBAL\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeRegisterInterface;
use SkyDiablo\DoctrineBundle\DBAL\Types\Traits\TypeTrait;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class UTCDateTimeType
 */
class UTCDateTimeType extends DateTimeType {

    /**
     * @var \DateTimeZone
     */
    static private $utc;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateTime) {
            $value = clone $value;
            $value->setTimezone(self::getUTC());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }

        $converted = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            self::getUTC()
        );

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $converted;
    }

    /**
     * @return \DateTimeZone
     */
    public static function getUTC()
    {
        return self::$utc ?: self::$utc = new \DateTimeZone('UTC');
    }

}