<?php
namespace mr\store;

/**
 * Class Query
 * @package mr\store
 * @datetime 2020/7/1 2:39 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Query
{
    /**
     * @var string
     * @datetime 2019/9/17 10:55 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $table;

    /**
     * @var array|string
     * @datetime 2019/9/17 10:56 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_select = '*';

    /**
     * @var array|string
     * @datetime 2019/9/17 10:58 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_where;

    /**
     * @var bool
     * @datetime 2019/9/18 11:19
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_isOr = false;

    /**
     * @var array|string
     * @datetime 2019/9/17 11:01 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_group;

    /**
     * @var array|string
     * @datetime 2019/9/17 11:02 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_order;

    /**
     * @var int
     * @datetime 2019/9/17 11:04 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_offset = 0;

    /**
     * @var int
     * @datetime 2019/9/17 11:06 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_limit = 1000;

    /**
     * @var array
     * @datetime 2019/9/18 10:41
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_params = [];

    /**
     * @param string $str
     * @param int    $num
     * @param string $glue
     * @return string
     * @datetime 2019/9/17 10:11 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function repeatAndRTrim($str, $num, $glue = ',')
    {
        return rtrim(str_repeat($str,$num),$glue);
    }

    /**
     * @param array | string $fields
     * @return $this
     * @datetime 2019/9/17 10:58 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function select($fields)
    {
        $this->_select = $fields;
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     * @datetime 2019/9/17 10:57 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param array|string $where
     * @param bool         $isOr
     * @return $this
     * @datetime 2019/9/17 10:58 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function where($where, $isOr = false)
    {
        $this->_where = $where;
        $this->_isOr  = $isOr;
        return $this;
    }

    /**
     * @param array|string $group
     * @return $this
     * @datetime 2019/9/17 11:01 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function group($group)
    {
        $this->_group = $group;
        return $this;
    }

    /**
     * @param array|string $order
     * @return $this
     * @datetime 2019/9/17 11:03 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function order($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     * @datetime 2019/9/17 11:05 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function offset($offset)
    {
        $this->_offset = $offset;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     * @datetime 2019/9/17 11:06 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function limit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * @param string $field
     * @return string
     * @datetime 2019/9/17 10:07 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function formatField($field)
    {
        return '`'.$field.'`';
    }

    /**
     * @param array|string $where
     * @param array        $params
     * @param bool         $isOr
     * @return string
     * @datetime 2019/9/17 10:46 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function analyWhere($where, &$params = [], $isOr = false)
    {
        if(empty($where)) {
            $where = ' ';
        }elseif (is_string($where)) {
            $where = ' WHERE '.$where;
        }elseif (is_array($where)) {
            $finallyWhere = [];

            foreach ($where as $field => $value) {
                $operator = '=';
                if(strpos($field,' ') > 0) {
                    list($field,$operator) = explode(' ',$field,2);
                }

                //绑参
                if(is_array($value)) {
                    if($operator == '=') {
                        $operator = 'IN';
                    }
                    $params = array_merge($params,$value);
                }else {
                    array_push($params,$value);
                }

                $field = static::formatField($field);
                $operator = strtoupper($operator);

                switch ($operator) {
                    case 'IN':
                        $subWhere = $field.' IN('.self::repeatAndRTrim('?,',count($value)).')';
                        break;
                    case 'BETWEEN':
                        $subWhere = $field.' BETWEEN ? AND ? ';
                        break;
                    case 'LIKE':
                        $subWhere = $field.' '.$operator.' ?';
                        break;
                    default:
                        $subWhere = $field.' '.$operator.'?';
                        break;
                }
                array_push($finallyWhere,'('.$subWhere.')');
            }

            $andOr = $isOr ? 'OR' : 'AND';
            $where = ' WHERE '.implode(' '.$andOr.' ',$finallyWhere);
            unset($finallyWhere);
        }

        return $where;
    }

    /**
     * @return array
     * @datetime 2019/9/18 10:41
     * @author roach
     * @email jhq0113@163.com
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @return string
     * @datetime 2019/9/18 10:42
     * @author roach
     * @email jhq0113@163.com
     */
    public function sql()
    {
        $this->_params = [];

        if(empty($this->_select)){
            $fields = '*';
        } elseif(is_array($this->_select)) {
            $fields = implode(',', array_map(function($field){
                return static::formatField($field);
            },$this->_select));
        }else {
            $fields = $this->_select;
        }

        $group = '';
        if(!empty($this->_group)) {
            if(is_array($this->_group)) {
                $group = ' GROUP BY '.implode(',', array_map(function($field){
                        return static::formatField($field);
                    },$this->_group));
            }else {
                $group = ' GROUP BY '.$this->_group;
            }
        }

        $order = '';
        if(!empty($this->_order)) {
            if(is_array($this->_order)) {
                $orderArr = [];
                foreach ($this->_order as $key => $value ) {
                    switch ($value) {
                        case SORT_ASC:
                            array_push($orderArr,static::formatField($key).' ASC');
                            break;
                        case SORT_DESC:
                            array_push($orderArr,static::formatField($key).' DESC');
                            break;
                        default:
                            array_push($orderArr,static::formatField($value));
                            break;
                    }
                }

                $order = ' ORDER BY '.implode(',',$orderArr);
            }else {
                $order = ' ORDER BY '.$this->_order;
            }
        }

        return 'SELECT '.$fields.' FROM '.static::formatField($this->table).static::analyWhere($this->_where,$this->_params,$this->_isOr).
            $group.$order.' LIMIT '.(string)$this->_offset.','.$this->_limit;
    }

    /**
     * @param string $table
     * @param array $rows
     * @param array $params
     * @param bool  $ignore
     * @return string
     * @datetime 2019/9/17 10:01 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function multiInsert($table, $rows, &$params = [], $ignore = false)
    {
        $fields = array_map(function($field){
            return static::formatField($field);
        },array_keys($rows[0]));


        $placeHolder = '('.self::repeatAndRTrim('?,',count($rows[0])).')';
        $placeHolder = self::repeatAndRTrim($placeHolder.',',count($rows));

        foreach ($rows as $row) {
            $params = array_merge($params,array_values($row));
        }

        return 'INSERT '.($ignore ? 'IGNORE' :'').' INTO '.static::formatField($table).'('.implode(',',$fields).')VALUES'.$placeHolder;
    }

    /**
     * @param string       $table
     * @param array|string $set
     * @param array|string $where
     * @param array        $params
     * @param bool         $isOr
     * @return string
     * @datetime 2019/9/17 10:43 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function updateAll($table,$set, $where, &$params = [], $isOr = false)
    {
        if(is_array($set)) {
            $sets = [];
            foreach ($set as $field => $value) {
                array_push($params,$value);
                array_push($sets,static::formatField($field).'=?');
            }

            $set = implode(',',$sets);
        }

        return 'UPDATE '.static::formatField($table).' SET '.$set.static::analyWhere($where,$params,$isOr);
    }

    /**
     * @param string       $table
     * @param array|string $where
     * @param bool         $isOr
     * @return string
     * @datetime 2019/9/17 10:49 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function deleteAll($table, $where, &$params = [], $isOr = false)
    {
        return 'DELETE FROM '.static::formatField($table).static::analyWhere($where,$params, $isOr);
    }
}