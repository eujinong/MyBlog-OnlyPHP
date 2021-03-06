<?php

if (! function_exists('base_url')) {
    /**
     * @param $url
     * @return string
     */
    function base_url($url = '')
    {
        // output: /myproject/index.php
        $currentPath = $_SERVER['PHP_SELF'];

        // output: localhost
        $hostName = $_SERVER['HTTP_HOST'];

        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        // return: http://localhost/myproject/
        return $protocol.$hostName."/myblog/".$url;
    }
}


if (! function_exists('escape')) {

    /**
     * @param $string
     */
    function escape ($string) {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('pretty_dump')) {

    /**
     * @param $data
     */
    function pretty_dump ($data) {
        echo '<pre>';
            var_dump($data);
        echo '</pre>';
    }
}

if (! function_exists('in_array_of_object')) {

    /**
     * @param array $array
     * @param $val
     * @return bool
     */
    function in_array_of_object (array $array, $val) {
        foreach ($array as $element) {
            if ($element->id == $val) {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('string_limit')) {

    function string_limit($value, $limit = 300, $end = '...')
    {
        $value = strip_tags($value);
        if (strlen($value) <= $limit) {
            return $value;
        }

        $excerpt   = substr($value, 0, $limit-3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
        $excerpt  .= $end;

        return $excerpt;
    }
}





