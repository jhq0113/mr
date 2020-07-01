<?php
use mr\base\Container;
use mr\store\Model;
use mr\helper\CsvHelper;

/**
 * Class Reason
 * @datetime 2020/7/1 3:31 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Reason extends Model
{
    public static $tableName = 'browserdoctor_reason_2019';
}

Container::set('db', [
    'class'    => 'mr\store\Mysql',
    'dsn'      => 'mysql:dbname=doctor_v6;host=10.143.153.41;port=3402',
    'userName' => 'doctor_v6',
    'password' => '3d073a742f449fc1',
    'charset'  => 'latin1'
]);

$exportFileName = __DIR__.'/'.Reason::$tableName.'.csv';
$pageSize       = 10000;
$reasons        = [
    1	=>	array('text' => '浏览器经常卡死','placeholder'=>'启动慢还是打开网页慢？'),
    2	=>	array('text' => '主页被劫持'),
    3	=>	array('text' => '网页打不开'),
    4	=>	array('text' => '网页自动跳转'),
    5	=>	array('text' => '网页打开速度慢'),
    6	=>	array('text' => '网页显示异常'),
    7 	=>	array('text' => '浏览器崩溃'),
    8	=>	array('text' => '广告弹窗多'),
    10  =>	array('text' => '启动时慢'),
    9 	=>	array('text' => '其他')
];

$contacts = [
    1	=> 'QQ',
    2	=> '飞信',
    3	=> '旺旺',
    4	=> 'MSN',
    5	=> '邮箱',
    6	=> '电话',
    7	=> '其他',
];

$result = Reason::getDb()->queryAll('SELECT COUNT(*) AS `num` FROM browserdoctor_reason_2019');
$nums = isset($result[0]['num']);
$totalPage = ceil($nums/$pageSize);


$query = Reason::find()->limit($pageSize);
for ($index=0; $index < $nums; $index++) {
    $query->offset($index * $pageSize);
    $rows = Reason::all($query);

    $data = ($index > 0) ? [] : [
        'ID',
        '反馈内容',
        'SE版本',
        '问题分类',
        '操作系统',
        '当前网页',
        '系统配置',
        'MID',
        '联系方式',
        '时间',
        'IP',
        '备注',
        '截图'
    ];

    array_map(function($row) use(&$data, $reasons, $contacts) {
        $url = array();
        $sys = trim(str_replace("\r\n", ';', $row['txt4']));
        $sys = trim(str_replace(array(',', '，'), ';', $sys));
        $sys = trim(str_replace('（', '(', $sys));
        $sys = trim(str_replace('）', ')', $sys));
        $tempsys = explode(';',str_replace('&amp;','&',$sys));
        $os = substr($tempsys[0],0,stripos($tempsys[0],'('));
        foreach($tempsys as $vv) {
            if(preg_match('/^http*/',$vv,$match)) {
                $url[] = $vv;
            }
        }
        $urlstr = implode(';',$url);
        $date = str_replace('-', '', $v['ext1']);
        $img=array("","","");
        //echo $value['hasimg'];
        if(intval($v['ext8'])>0)
        {
            for ($i=0; $i <intval($v['ext8']); $i++)
            {
                $img[$i]="http://p2.qhimgs3.com/d/doctor/{$date}/{$v['ext7']}_".($i+1).".jpg";
            }
        }

        array_push($data, [
            $row['id'],
            $row['txt1'],
            $row['version'],
            $reasons[ $row['opt1'] ]['text'],
            $os,
            $urlstr,
            $sys,
            $row['ext2'],
            $contacts[ $row['opt3'] ].':'.$row['txt3'],
            date('Y/m/d H:i', $row['addtime']),
            $row['ext5'],
            $row['txt2'],
            $row['txt2'],
            $img[0],
            $img[1],
            $img[2],
        ]);
    }, $rows);

    CsvHelper::write($exportFileName, $data, $index>0);

    if($index > 1) {
        return;
    }
}
