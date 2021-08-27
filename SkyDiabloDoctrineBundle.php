<?php

namespace SkyDiablo\DoctrineBundle;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use SkyDiablo\DoctrineBundle\Bundle\RegisterDBALTypeBundle;
use SkyDiablo\DoctrineBundle\DBAL\Types\Common\UTCDateTimeType;

class SkyDiabloDoctrineBundle extends RegisterDBALTypeBundle
{
    protected function registerDBALTypes()
    {
        Type::overrideType(Types::DATETIME_MUTABLE, UTCDateTimeType::class); // all datetime objects will be stored as UTC
        Type::overrideType(Types::DATETIMETZ_MUTABLE, UTCDateTimeType::class); // all datetime objects will be stored as UTC
    }

}
