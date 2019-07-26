<?php


namespace SkyDiablo\DoctrineBundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class RegisterTypeBundle
 */
abstract class RegisterDBALTypeBundle extends Bundle
{
    public function boot()
    {
        $this->registerDBALTypes();
    }

    public function build(ContainerBuilder $container)
    {
        $this->registerDBALTypes();
    }


    abstract protected function registerDBALTypes();

}