<?php

/**
 * Created by PhpStorm.
 * User: nekic
 * Date: 4/23/17
 * Time: 3:02 PM
 */
class common
{
    public static function ajaxReturn($data)
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }
}