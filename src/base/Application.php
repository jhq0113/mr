<?php
namespace mr\base;

/**
 * Class Application
 * @package mr
 * @datetime 2020/6/5 6:05 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Application
{
    /**
     * @var array
     * @datetime 2020/6/5 6:05 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $params = [];

    /**
     * @datetime 2020/6/5 6:06 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function run()
    {
        require $this->params[0];
    }
}