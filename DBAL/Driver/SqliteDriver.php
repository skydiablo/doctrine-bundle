<?php

declare(strict_types=1);


namespace SkyDiablo\DoctrineBundle\DBAL\Driver;

use Doctrine\DBAL\Driver\PDOSqlite\Driver as BaseDriver;


class SqliteDriver extends BaseDriver
{

    /**
     * Attempts to create a connection with the database.
     *
     * The usage of NULL to indicate empty username or password is deprecated. Use an empty string instead.
     *
     * @param mixed[] $params All connection parameters passed by the user.
     * @param string|null $username The username to use when connecting.
     * @param string|null $password The password to use when connecting.
     * @param mixed[] $driverOptions The driver options to use when connecting.
     *
     * @return \Doctrine\DBAL\Driver\Connection The database connection.
     * @throws \Doctrine\DBAL\DBALException
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        if (!isset($driverOptions['userDefinedFunctions'])) {
            $driverOptions['userDefinedFunctions'] = [];
        }

        // Adding all needed sql function
        $driverOptions['userDefinedFunctions']['log'] = ['callback' => 'log', 'numArgs' => 2];
        $driverOptions['userDefinedFunctions']['log10'] = ['callback' => 'log10', 'numArgs' => 1];

        return parent::connect($params, $username, $password, $driverOptions);
    }


}