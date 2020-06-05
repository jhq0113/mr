<?php
/**
 * Class Autoload
 * @package mr
 * @datetime 2020/6/5 5:29 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Autoload
{
    /**
     * @var array
     * @datetime 2019/8/30 19:27
     * @author roach
     * @email jhq0113@163.com
     */
    private static $_namespaceMap = [];

    /**
     * Autoload constructor.
     */
    private function __construct(){}

    /**
     * @datetime 2019/8/30 19:27
     * @author roach
     * @email jhq0113@163.com
     */
    private function __clone(){}

    /**
     * @param $baseNamespace
     * @param $dir
     * @datetime 2019/8/30 19:27
     * @author roach
     * @email jhq0113@163.com
     */
    static public function registerNamespace($baseNamespace,$dir)
    {
        self::$_namespaceMap[ $baseNamespace ] = $dir;
    }

    /**
     * @param $class
     * @datetime 2019/8/30 19:27
     * @author roach
     * @email jhq0113@163.com
     */
    static public function autoload($class)
    {
        $position = strpos($class,'\\');
        $prefix = substr($class,0, $position);
        if($prefix === 'mr') {
            $dir = __DIR__;
        }else {
            if(!isset(self::$_namespaceMap[ $prefix ])) {
                return;
            }
            $dir = self::$_namespaceMap[ $prefix ];
        }

        $fileName = $dir.str_replace('\\','/',substr($class, $position)).'.php';
        if(file_exists($fileName)) {
            require $fileName;
        }
    }
}