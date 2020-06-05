<?php
namespace mr\helper;

/**
 * Class HArray
 * @package mr\helper
 * @datetime 2020/6/5 5:38 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class HArray extends BaseHelper
{
    /**
     * @param array $a
     * @param array $b
     * @return array|mixed
     * @datetime 2020/6/5 5:49 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $result = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $result)) {
                        $result[] = $v;
                    } else {
                        $result[$k] = $v;
                    }
                } elseif (is_array($v) && isset($result[$k]) && is_array($result[$k])) {
                    $result[$k] = self::merge($result[$k], $v);
                } else {
                    $result[$k] = $v;
                }
            }
        }
        return $result;
    }
}