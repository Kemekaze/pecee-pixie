<?php

namespace Pecee\Pixie\ConnectionAdapters;

/**
 * Class Pgsql
 *
 * @package Pecee\Pixie\ConnectionAdapters
 */
class Pgsql extends BaseAdapter
{
    /**
     * @param array $config
     *
     * @return mixed
     * @throws Exception
     */
    protected function doConnect(array $config)
    {
        if (extension_loaded('pdo_pgsql') === false) {
            throw new Exception(sprintf('%s library not loaded', 'pdo_pgsql'));
        }

        $connectionString = "pgsql:host={$config['host']};dbname={$config['database']}";

        if (isset($config['port']) === true) {
            $connectionString .= ";port={$config['port']}";
        }

        /**
         * @var \PDO $connection
         */
        $connection = $this->container->build(
            \PDO::class,
            [$connectionString, $config['username'], $config['password'], $config['options']]
        );

        if (isset($config['charset']) === true) {
            $connection->prepare("SET NAMES '{$config['charset']}'")->execute();
        }

        if (isset($config['schema']) === true) {
            $connection->prepare("SET search_path TO '{$config['schema']}'")->execute();
        }

        return $connection;
    }
}
