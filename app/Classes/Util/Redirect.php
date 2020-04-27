<?php
namespace Classes\Util;

class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            echo '<script> window.location="'.$location.'"</script>';
            exit();
        }
    }
}