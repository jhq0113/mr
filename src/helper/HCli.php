<?php
namespace mr\helper;

/**
 * Class HCli
 * @package mr\helper
 * @datetime 2020/6/5 5:53 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class HCli extends BaseHelper
{
    /**判断是否为Cli环境
     * @return bool
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    /**获取Cli参数
     * @return array
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function params()
    {
        return array_slice($_SERVER['argv'],1);
    }

    /**输出消息
     * @param string $msg
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function msg($msg)
    {
        echo $msg.PHP_EOL;
    }

    /**
     * @param string $msg
     * @param array $context
     * @datetime 2019/8/30 18:05
     * @author roach
     * @email jhq0113@163.com
     */
    static public function info($msg,array $context=[])
    {
        $msg = HString::interpolate($msg,$context);
        $msg = "\033[1;32m".date('Y-m-d H:i:s')." [info] ".$msg." \033[0m";
        self::msg($msg);
    }

    /**
     * @param string $msg
     * @param array $context
     * @datetime 2019/8/30 18:05
     * @author roach
     * @email jhq0113@163.com
     */
    static public function warn($msg,array $context=[])
    {
        $msg = HString::interpolate($msg,$context);
        $msg = "\033[1;33m".date('Y-m-d H:i:s')." [warn] ".$msg." \033[0m";
        self::msg($msg);
    }

    /**
     * @param string $msg
     * @param array $context
     * @datetime 2019/8/30 18:06
     * @author roach
     * @email jhq0113@163.com
     */
    static public function error($msg,array $context=[])
    {
        $msg = HString::interpolate($msg,$context);
        $msg = "\033[1;31m".date('Y-m-d H:i:s')." [error] ".$msg." \033[0m";
        self::msg($msg);
    }
}