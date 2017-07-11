<?php

/**
 * Created by PhpStorm.
 * User: nekic
 * Date: 6/11/17
 * Time: 4:45 PM
 */
class user
{
    public static function login()
    {
        $name = $_POST['name'];
        $passwd = $_POST['passwd'];

        if($name !=='nekic' && $passwd !=='qinlianhua') { // 登陆失败
            return false;
        }

        // 登陆成功
        $token = 'nekic|qinlianhua|'.  time();
        $token = self::enCode($token);
        setcookie('token',$token);
        return true;
    }

    public static function checkAuth()
    {
        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

        if(!$token) {
            return false;
        }
//        $token = $header['Auth_token'];
        $token = self::deCode($token);
        $tokenArr = explode('|',$token);
//        var_dump($tokenArr);
        if($tokenArr[0] !=='nekic' && $token[1] !=='qinlianhua') {
            return false;
        }

//        echo 1232132;

//        // 判断token 有效时间
//        if(time() - $token[3] > 86400) { // 已失效
//            return false;
//        }
//
//        // 验证通过，重新设置 token
//        $token = 'nekic|qinlianhua' . time();
//        $token = self::enCode($token);
//        setcookie('token',$token);
        return true;
    }


    /**
     * 通用加密
     * @param String $string 需要加密的字串
     * @param String $skey 加密EKY
     * @return String
     */
    private static function enCode($string = '', $skey = 'nekiencodekey123459982') {
        $skey = array_reverse(str_split($skey));
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach ($skey as $key => $value) {
            $key < $strCount && $strArr[$key].=$value;
        }
        return str_replace('=', 'O0O0O', join('', $strArr));
    }

    /**
     * 通用解密
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @return String
     */
    private static function deCode($string = '', $skey = 'nekiencodekey123459982') {
        $skey = array_reverse(str_split($skey));
        $strArr = str_split(str_replace('O0O0O', '=', $string), 2);
        $strCount = count($strArr);
        foreach ($skey as $key => $value) {
            $key < $strCount && $strArr[$key] = rtrim($strArr[$key], $value);
        }
        return base64_decode(join('', $strArr));
    }

}