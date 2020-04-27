<?php
namespace Classes\Util;

use Classes\Config\Config;
use Classes\Util\Session;

class Token
{
    public static function generate()
    {
        return Session::set(Config::get('session/token_name'), md5(uniqid()));
    }

    public static function check($token)
    {
        $tokenName = Config::get('session/token_name');
        if (Session::exists($tokenName)  && $token === Session::get($tokenName)) {
            Session::unsetSession($tokenName);
            return true;
        }

        return false;
    }
}