<?php
namespace mr\store;

use mr\base\Container;

/**
 * Class Model
 * @package mr\store
 * @datetime 2020/7/1 2:42 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Model
{
    /**
     * @var string
     * @datetime 2020/7/1 2:42 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static $tableName;

    /**
     * @return Mysql
     * @datetime 2020/7/1 2:42 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function getDb()
    {
        return Container::get('db');
    }

    /**
     * @return Query
     * @datetime 2020/7/1 2:44 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function find()
    {
        return (new Query())
            ->from(static::$tableName);
    }

    /**
     * @param Query $query
     * @return array
     * @throws \Exception
     * @datetime 2020/7/1 2:45 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function all(Query $query)
    {
        $sql = $query->sql();
        return static::getDb()->queryAll($sql, $query->getParams());
    }

    /**
     * @param Query $query
     * @return array
     * @throws \Exception
     * @datetime 2020/7/1 2:47 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function one(Query $query)
    {
        $query->limit(1);
        $sql = $query->sql();
        $rows = static::getDb()->queryAll($sql, $query->getParams());
        if(isset($rows[0])) {
            return $rows[0];
        }

        return [];
    }

    /**
     * @param array $rows
     * @param bool  $ignore
     * @return int
     * @throws \Exception
     * @datetime 2020/7/1 2:52 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function batchInsert($rows, $ignore = false)
    {
        $params = [];
        $sql = Query::multiInsert(static::$tableName, $rows, $params, $ignore);
        return static::getDb()->execute($sql, $params);
    }

    /**
     * @param array $row
     * @param bool  $ignore
     * @return int|string
     * @throws \Exception
     * @datetime 2020/7/1 2:55 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function insert($row, $ignore = false)
    {
        $params = [];
        $db     = static::getDb();
        $sql    = Query::multiInsert(static::$tableName, [ $row ], $params, $ignore);
        $rows   = $db->execute($sql, $params);
        return $ignore ? $rows : $db->lastInsertId();
    }

    /**
     * @param array|string $where
     * @param bool         $isOr
     * @return int
     * @throws \Exception
     * @datetime 2020/7/1 2:49 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function deleteAll($where, $isOr = false)
    {
        $params = [];
        $sql = Query::deleteAll(static::$tableName, $where, $params, $isOr);
        return static::getDb()->execute($sql, $params);
    }

    /**
     * @param array|string $set
     * @param array|string $where
     * @param bool         $isOr
     * @return int
     * @throws \Exception
     * @datetime 2020/7/1 2:50 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function updateAll($set, $where, $isOr = false)
    {
        $params = [];
        $sql = Query::updateAll(static::$tableName, $set, $where, $params, $isOr);
        return static::getDb()->execute($sql, $params);
    }
}