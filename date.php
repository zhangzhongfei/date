<?php
/**
 * Created by PhpStorm.
 * User: zhangzhongfei
 * Date: 2017/7/11
 * Time: 8:56
 * 操作日期的类
 */
class date
{
    private static $year;
    private static $month;
    private static $day;
    private static $time;
    private static $date;


    public function __construct($y = '', $m = '', $d = '')
    {
        self::$year  = isset($y) ? $y : date('y', time());
        self::$month = isset($m) ? $m : date('m', time());
        self::$day   = isset($d) ? $d : date('d', time());
        self::$time  = self::$year . '-' . self::$month;
        self::$date  = self::$year . '-' . self::$month . '-' . self::$day;


    }
	
	
    /**
     * 获取当前所在的时间，这个月有几天
     * @param string $time
     * @return bool|string
     */
    public function MonthDays($time = '')
    {

        $time = $time ? $time : time();

        $y = date('y', $time); //指定的年
        $m = date('m', $time); //指定的月
        $d = date('j', mktime(0, 0, 1, ($m == 12 ? 1 : $m + 1), 1, ($m == 12 ? $y + 1 : $y)) - 24 * 3600);

        return $d;
    }

    /**
     * 获取指定日期星期几
     * @param string $time
     * @param int    $day
     * @return bool|string
     */
    public function GetWeek($time = '', $day = 1)
    {
        $time = $time ? $time : time();
        $y = date('y', $time); //指定的年
        $m = date('m', $time); //指定的月
        $date = mktime(0, 0, 0, $m, $day, $y);
        $number_wk = date("w", $date);
        return $number_wk;
    }

    /**
     * 获取一年的各月起止时间
     * @return array
     */
    public function getWeeksOfMonth()
    {
        $months = [
            'january','february','march','april','may','june','july','august ','september','october','november','december'
        ];
        $weeks = [
            '1'=>'first','2'=>'second','3'=>'third','4'=>'fourth'
        ];
        $res = [];
        foreach($months as $key=>$value){
            for($i=1;$i<=4;$i++){
                $monday = date('Y-m-d', strtotime($weeks[$i].' monday of '.$value));
                $sunday = date('Y-m-d' ,strtotime($monday . '+6day'));
                $res[$value][] = $monday. ','. $sunday;
            }
        }
        return $res;
    }

    /**
     * 计算一个月的天数
     */
    public function monthDay()
    {
        $d=date('j',mktime(0,0,1,(self::$month==12?1:self::$month+1),1,(self::$month==12?self::$year+1:self::$year))    -24*3600);
        return $d;
    }

    /**
     * 计算一个月内的星期六 星期天
     */
    public function sundayAndSaturday()
    {


        //计算五月的星期六星期天
        $today=getdate(strtotime(self::$time));
        $i=mktime(0,0,0,$today['mon'],1,$today['year']);
        $sunday   = [];
        $saturday = [];
        while(1){
            $day=getdate($i);
            if ($day['mon'] != $today['mon']) break;
            if ($day['wday'] == 0){
                $mon   = str_pad($day['mon'], 2, '0', STR_PAD_LEFT);
                $mday  = str_pad($day['mday'], 2, '0', STR_PAD_LEFT);
                $sunday[] = $day['year']."-".$mon."-".$mday;
            }
            if ($day['wday'] == 6){
                $mon   = str_pad($day['mon'], 2, '0', STR_PAD_LEFT);
                $mday  = str_pad($day['mday'], 2, '0', STR_PAD_LEFT);
                $saturday[] = $day['year']."-".$mon."-".$mday;
            }
            $i+=24*3600;
        }
        $data = [
            'sunday' => $sunday,
            'saturday' => $saturday
        ];
        return $data;
    }


    /**
     * 计算月的起止日期
     * 此处的自然月不是一号到30号 如果需要可以修改
     */
    public function startAndEndTime()
    {
        $month = self::$month;
        $year  = self::$year;
        if ($month == 1) {
            $premonth = 12;
            $year -= 1;
        }else {
            $premonth = $month - 1;
        }
        $start = $year . '-' . $premonth . '-' . '21';
        $end = $year . '-' . $month . '-' . '20';
        return [$start, $end, $year, $month];
    }


    /**
     * 获取本周周一到周日的起止日期
     * @return array
     */
    public function getWeek()
    {
        $week = date('w', strtotime(self::date));
        $weeks[] = date('Y-m-d',strtotime( '+'. 1-$week .' days' ));
        $weeks[] = date('Y-m-d',strtotime( '+'. 7-$week .' days' ));
        return $weeks;
    }


    /**
     * 获取上周周一到周日
     * @return array
     */
    public function getPrevWeek()
    {
        $dates = [];
        $time  = strtotime(self::$date.' 12:00:00');
        $w     = date('w', $time);
        $nextMonday = $w;
        for($i = $nextMonday; $i< $nextMonday+7; $i++){
            $dates[] = date('Y-m-d', $time - 3600*24*$i);
        }
        return $dates;
    }

    /**
     * 获取下一周
     * @return array
     */
    public function getNextWeek()
    {
        $dates = [];
        $time  = strtotime(self::$date.' 12:00:00');
        $w     = date('w', $time);
        if($w == 0){
            $nextMonday = 1;
        } else {
            $nextMonday = 7 - $w + 1;
        }
        for($i = $nextMonday; $i<$nextMonday + 7; $i++){
            $dates[] = date('Y-m-d', $time + 3600*24*$i);
        }
        return $dates;
    }


}