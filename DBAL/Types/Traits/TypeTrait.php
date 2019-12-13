<?php

namespace SkyDiablo\DoctrineBundle\DBAL\Types\Traits;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class TypeTrait
 */
trait TypeTrait
{

    /**
     * register type in doctrine framework
     */
    public static function register()
    {
        if (!self::hasType(static::__TYPE_NAME)) {
            self::addType(static::__TYPE_NAME, static::class);
        }
    }

    public function getName(): string
    {
        return static::__TYPE_NAME;
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

}