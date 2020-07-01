<?php
namespace mr\helper;

/**
 * Class CsvHelper
 * @package mr\helper
 * @datetime 2020/7/1 3:44 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class CsvHelper extends BaseHelper
{
    /**
     * @param string $fileName
     * @param array  $rows
     * @param bool   $append
     * @return bool
     * @datetime 2020/7/1 3:53 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function write($fileName, $rows, $append = false)
    {
        $dir = dirname($fileName);
        if(!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $mode = $append ? 'a+' : 'w+';
        $file = fopen($fileName, $mode);
        if(!$file) {
            return false;
        }

        array_map(function($value) use($file) {
            $row = array_map(function($val) {
                return mb_convert_encoding($val, 'GBK', 'UTF-8');
            }, $value);

            fputcsv($file, $row);
        }, $rows);

        fclose($file);
        return true;
    }
}