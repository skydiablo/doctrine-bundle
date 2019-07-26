<?php


namespace SkyDiablo\DoctrineBundle\Factory;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class ObjectFactory
 */
abstract class ObjectFactory
{

    /**
     * @return mixed
     */
    protected function createObject()
    {
        return call_user_func_array(array($this, 'doCreateObject'), func_get_args());
    }

    /**
     * Create object instance
     * @return mixed
     */
    abstract protected function doCreateObject();

}