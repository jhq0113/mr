<?php
namespace mr\store;

use mr\helper\HString;

/**
 * Class Mysql
 * @package mr\db
 * @datetime 2020/6/5 5:23 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Mysql
{
    /**是否为只读库
     * @var bool
     * @datetime 2020/5/10 1:28 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $readOnly = false;

    /**
     * @var string
     * @datetime 2020/5/10 10:38 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $dsn;

    /**
     * @var string
     * @datetime 2020/5/10 10:39 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $userName;

    /**
     * @var string
     * @datetime 2020/5/10 10:39 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $password;

    /**
     * @var string
     * @datetime 2020/5/10 11:53 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $charset = 'utf8';

    /**
     * @var array
     * @datetime 2020/5/10 10:42 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $attributes = [];

    /**
     * @var array
     * @datetime 2020/5/10 11:45 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected $_defaultAttributes = [
        \PDO::ATTR_TIMEOUT          => 10,
        \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
    ];

    /**
     * @var \PDO
     * @datetime 2020/5/10 10:37 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected $_pdo;

    /**
     * @throws \Exception
     * @datetime 2020/7/1 2:37 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function open()
    {
        if(!is_null($this->_pdo)) {
            return;
        }

        try {
            $this->attributes = array_merge($this->_defaultAttributes, $this->attributes);
            //连接数据库
            $this->_pdo = new \PDO($this->dsn, $this->userName, $this->password, $this->attributes);
            //设置编码
            $this->_pdo->exec('SET NAMES '.$this->_pdo->quote($this->charset));
        }catch (\PDOException $exception) {
            $msg = HString::interpolate('connect db[{dsn}] failed', [
                'dsn' => $this->dsn,
            ]);
            $this->_pdo = null;
            throw new \Exception($msg);
        }
    }

    /**
     * @param string $sql
     * @param array  $params
     * @return int
     * @throws \Exception
     * @datetime 2020/7/1 2:37 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function execute($sql, $params = [])
    {
        if($this->readOnly) {
            throw new \Exception('connection is readonly');
        }

        $this->open();
        $statement = $this->_pdo->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * @param string $sql
     * @param array  $params
     * @return array
     * @throws \Exception
     * @datetime 2020/7/1 2:38 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function queryAll($sql, $params = [])
    {
        $this->open();
        $statement = $this->_pdo->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result;
    }

    /**
     * @return string
     * @datetime 2020/7/1 2:54 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }


    /**
     * @return bool
     * @throws \Exception
     * @datetime 2020/7/1 2:36 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function begin()
    {
        if($this->readOnly) {
            throw new \Exception('connection is readonly');
        }

        $this->open();
        return $this->_pdo->beginTransaction();
    }

    /**
     * @return bool
     * @datetime 2020/5/10 1:16 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function rollback()
    {
        return $this->_pdo->rollBack();
    }

    /**
     * @return bool
     * @datetime 2020/5/10 1:23 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function commit()
    {
        return $this->_pdo->commit();
    }
}