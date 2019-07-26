<?php

namespace SkyDiablo\DoctrineBundle;

use Doctrine\DBAL\Types\Type;
use SkyDiablo\DoctrineBundle\Bundle\RegisterDBALTypeBundle;
use SkyDiablo\DoctrineBundle\DBAL\Types\Common\UTCDateTimeType;

class SkyDiabloDoctrineBundle extends RegisterDBALTypeBundle
{
    protected function registerDBALTypes()
    {
        Type::overrideType(Type::DATETIME, UTCDateTimeType::class); // all datetime objects will be stored as UTC
        Type::overrideType(Type::DATETIMETZ, UTCDateTimeType::class); // all datetime objects will be stored as UTC
    }

}
