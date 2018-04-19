<?php

namespace Util;

class Utility
{
    /**
     * Get Array Value By Path
     *
     * @param  string $path Example: key1.key2.key3...keyn
     * @param  array  $array
     * @return mixed
     */
    public static function getArrayValue($path, array $array = array(), $default = null, $delimiter = '.')
    {
        $keysName = explode($delimiter, $path);
        $value = $array;

        if (empty($array)) {
            return $default;
        }

        foreach ($keysName as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }

        return $value;
    }

    public static function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function getAppDomain()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        $hostName = $_SERVER['SERVER_NAME'];

        return $protocol.$hostName;
    }

    public static function getIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getOS()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform  = "Unknown";
        $osArray     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );

        foreach ($osArray as $regex => $value)
            if (preg_match($regex, $userAgent))
                $osPlatform = $value;

        return $osPlatform;
    }

    public static function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browser        = "Unknown";
        $browserArray = array(
                                '/msie/i'      => 'Internet Explorer',
                                '/firefox/i'   => 'Firefox',
                                '/safari/i'    => 'Safari',
                                '/chrome/i'    => 'Chrome',
                                '/edge/i'      => 'Edge',
                                '/opera/i'     => 'Opera',
                                '/netscape/i'  => 'Netscape',
                                '/maxthon/i'   => 'Maxthon',
                                '/konqueror/i' => 'Konqueror',
                                '/mobile/i'    => 'Handheld Browser'
                         );
        foreach ($browserArray as $regex => $value)
            if (preg_match($regex, $userAgent))
                $browser = $value;

        return $browser;
    }

    public static function getLang()
    {
        $lang = '';
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        if (preg_match('/^([a-z]+\-[A-Z]+)\,.*$/', $acceptLang, $regs)) {
        	$lang = $regs[1];
        }

        return $lang;
    }

    public static function buildUrl($controller, $action = '', array $params = [], $keep = false, $absolute = false)
    {
        $request = new Request();
        if ($keep) {
            $params = array_replace_recursive($request->getQuery(), $params);
        }

        if (empty($controller)) {
            $controller = $request->getQueryParam('controller');
        }
        if (empty($action) && $keep) {
            $action = $request->getQueryParam('action');
        }

        $params['controller'] = $controller;
        if (!empty($action)) {
            $params['action'] = $action;
        }

        foreach ($params as $key => $value) {
            if (empty($params[$key])) {
                unset($params[$key]);
            }
        }

        if ($absolute) {
            return static::getAppDomain().'/?'.http_build_query($params);
        } else {
            return '/?'.http_build_query($params);
        }
    }

    /**
     * Generate Slug from string Unicode
     *
     * @param string $str
     * @return string
     */
    public static function slugify($str)
    {
        $tmp = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $tmp = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $tmp);
        $tmp = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $tmp);
        $tmp = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $tmp);
        $tmp = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $tmp);
        $tmp = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $tmp);
        $tmp = preg_replace("/(đ)/", 'd', $tmp);
        $tmp = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $tmp);
        $tmp = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $tmp);
        $tmp = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $tmp);
        $tmp = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $tmp);
        $tmp = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $tmp);
        $tmp = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $tmp);
        $tmp = preg_replace("/(Đ)/", 'D', $tmp);
        $tmp = strtolower(trim($tmp));
        //$tmp = str_replace('-','',$tmp);
        $tmp = str_replace(' ', '-', $tmp);
        $tmp = str_replace('_', '-', $tmp);
        $tmp = str_replace('.', '', $tmp);
        $tmp = str_replace("'", '', $tmp);
        $tmp = str_replace('"', '', $tmp);
        $tmp = str_replace('"', '', $tmp);
        $tmp = str_replace('"', '', $tmp);
        $tmp = str_replace("'", '', $tmp);
        $tmp = str_replace('̀', '', $tmp);
        $tmp = str_replace('&', '', $tmp);
        $tmp = str_replace('@', '', $tmp);
        $tmp = str_replace('^', '', $tmp);
        $tmp = str_replace('=', '', $tmp);
        $tmp = str_replace('+', '', $tmp);
        $tmp = str_replace(':', '', $tmp);
        $tmp = str_replace(',', '', $tmp);
        $tmp = str_replace('{', '', $tmp);
        $tmp = str_replace('}', '', $tmp);
        $tmp = str_replace('?', '', $tmp);
        $tmp = str_replace('\\', '', $tmp);
        $tmp = str_replace('/', '', $tmp);
        $tmp = str_replace('quot;', '', $tmp);
        return $tmp;
    }

    /**
     * Format lại hiển thị tiền Việt
     *
     * @param float $money
     * @return type
     */
    public static function vietnameseMoneyFormat($money, $symbol = '') {
        if ($money == 0) {
            return 0;
        }

    	return number_format($money, 0, '.', ',') . ' ' . $symbol;
    }
}
