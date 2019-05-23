<?php
use app\components\Request;
ignore_user_abort();
//关掉浏览器，PHP脚本也可以继续执行.
set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
$interval=5;// 每隔半小时运行
do{
    Request::set_scanNumber();
    sleep($interval);// 等待5分钟
}while(true);